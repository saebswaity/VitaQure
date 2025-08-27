<?php

use Twilio\Rest\Client;
use App\Models\Setting;
use App\Models\Patient;
use App\Models\Group;
use App\Models\Doctor;
use App\Mail\PatientCodeMail;
use App\Mail\TestsNotification;
use App\Mail\ReceiptMail;
use App\Mail\ReportMail;

//get system currency
if (!function_exists('get_currency')) 
{
   function get_currency()
   {
        if(cache()->has('currency'))
        {
            $currency=cache('currency');
        }
        else{
            $setting=setting('info');
            $currency=$setting['currency'];
            cache()->put('currency',$currency);
        }
        return $currency;
   }

}

//get formated price of things
if (!function_exists('formated_price')) 
{
   function formated_price($price)
   {
        if(cache()->has('currency'))
        {
            return $price.' '.cache()->get('currency');
        }
        else{

            $setting=Setting::where('key','info')->first()['value'];
            $setting=json_decode($setting,true);
            $currency=$setting['currency'];
            cache()->put('currency',$currency);
        }

        return $currency;
   }

}

//send sms
if (!function_exists('send_sms')) 
{
   function send_sms($to,$message)
   {
        $sms_setting=setting('sms');

        if(!empty($sms_setting['sid'])&&!empty($sms_setting['token'])&&!empty($sms_setting['from']))
        {
            // Your Account SID and Auth Token from twilio.com/console
            $sid = $sms_setting['sid'];
            $token = $sms_setting['token'];
            $client = new Client($sid, $token);

            // Use the client to do fun stuff like send text messages!
            try{
                $client->messages->create(
                    // the number you'd like to send the message to
                    $to,
                    [
                        // A Twilio phone number you purchased at twilio.com/console
                        'from' => $sms_setting['from'],
                        // the body of the text message you'd like to send
                        'body' => $message
                    ]
                );
            }
            catch(\Exception $e){
               //error
            }
        }

    }
}

//send notifications via mail and sms
if (!function_exists('send_notification')) 
{
   function send_notification($type,$patient,$group=null)
   {
       //send mail notification 
       $email_settings=setting('emails');

       if(isset($email_settings[$type])&&$email_settings[$type]['active']==true)
       {
           if(!empty($patient['email']))
           {
               if($type=='patient_code')
               {
                   try{
                        \Mail::to($patient['email'])->send(new PatientCodeMail($patient));
                   }
                   catch(\Exception $e)
                   {
                      //
                   }
               }
               elseif($type=='receipt')
               {
                    try{
                        \Mail::to($patient['email'])->send(new ReceiptMail($patient,$group));
                    }
                    catch(\Exception $e)
                    {
                        //
                    }
               }
               elseif($type=='report')
               {
                    try{
                        \Mail::to($patient['email'])->send(new ReportMail($patient,$group));
                    }
                    catch(\Exception $e)
                    {
                        //
                    }
               }
           }

       }

       //send sms
       $sms_settings=setting('sms');

       if(isset($sms_settings[$type])&&$sms_settings[$type]['active']==true)
       {
           if(!empty($patient['phone']))
           {
                $message=str_replace(
                    ['{patient_code}','{patient_name}'],
                    [$patient['code'],$patient['name']],
                    $sms_settings[$type]['message']
                );

                send_sms($patient['phone'],$message);
           }
       }
   }
}

//get json setting as array
if (!function_exists('setting')) 
{  
    function setting($key)
    {
        $setting = Setting::where('key', $key)->first();
        if (!$setting) {
            return null;
        }
        return json_decode($setting['value'], true);
    }
}

//generate  pdf
if (!function_exists('generate_pdf')) 
{  
    //type (1) => tests report 
    //type (2) => receipt
    //type (3) => accounting report
    //type (4) => accounting doctor report

    function generate_pdf($data='',$type=1)
    {
        //reports settings with safe defaults
        $reports_settings=setting('reports');
        if(!$reports_settings||!is_array($reports_settings)){
            $defaultFont='Arial';
            $defaultSize='14px';
            $defaultColor='#000000';
            $reports_settings=[
                'test_title'=>['color'=>$defaultColor,'font-size'=>'18px','font-family'=>$defaultFont],
                'test_name'=>['color'=>$defaultColor,'font-size'=>$defaultSize,'font-family'=>$defaultFont],
                'test_head'=>['color'=>$defaultColor,'font-size'=>$defaultSize,'font-family'=>$defaultFont],
                'unit'=>['color'=>$defaultColor,'font-size'=>$defaultSize,'font-family'=>$defaultFont],
                'reference_range'=>['color'=>$defaultColor,'font-size'=>$defaultSize,'font-family'=>$defaultFont],
                'result'=>['color'=>$defaultColor,'font-size'=>$defaultSize,'font-family'=>$defaultFont],
                'status'=>['color'=>$defaultColor,'font-size'=>$defaultSize,'font-family'=>$defaultFont],
                'comment'=>['color'=>$defaultColor,'font-size'=>$defaultSize,'font-family'=>$defaultFont],
                'antibiotic_name'=>['color'=>$defaultColor,'font-size'=>$defaultSize,'font-family'=>$defaultFont],
                'sensitivity'=>['color'=>$defaultColor,'font-size'=>$defaultSize,'font-family'=>$defaultFont],
                'commercial_name'=>['color'=>$defaultColor,'font-size'=>$defaultSize,'font-family'=>$defaultFont],
            ];
        }

        //info setting
        $info_settings=setting('info');

        $pdf_name=time().'.pdf';

        //get header , body , footer from public/img and guard if missing
        $headerPath=public_path('img/report_header.jpg');
        $backgroundPath=public_path('img/report_background.png');
        $footerPath=public_path('img/report_footer.jpg');

        $report_header=null; $report_background=null; $report_footer=null;
        if(is_file($headerPath)){
            $report_header='data:'.mime_content_type($headerPath).';base64,'.base64_encode(file_get_contents($headerPath));
        }
        if(is_file($backgroundPath)){
            $report_background='data:'.mime_content_type($backgroundPath).';base64,'.base64_encode(file_get_contents($backgroundPath));
        }
        if(is_file($footerPath)){
            $report_footer='data:'.mime_content_type($footerPath).';base64,'.base64_encode(file_get_contents($footerPath));
        }

        if($type==1)
        {
            $group=$data;
            $pdf = PDF::loadView('pdf.report',compact('group','reports_settings','info_settings','type','report_header','report_background','report_footer'));
        }
        elseif($type==2){
            $group=$data;
            $pdf = PDF::loadView('pdf.receipt',compact('group','reports_settings','info_settings','type','report_header','report_background','report_footer'));
        }
        elseif($type==3)
        {
            $pdf = PDF::loadView('pdf.accounting',compact('data','reports_settings','info_settings','type'));
        }
        elseif($type==4)
        {
            $pdf = PDF::loadView('pdf.doctor_report',compact('data','reports_settings','info_settings','type'));
        }

        //ensure directory exists under public/uploads/pdf
        $pdfDir=public_path('uploads/pdf');
        if(!is_dir($pdfDir)){@mkdir($pdfDir,0775,true);}        

        $pdf->save($pdfDir.DIRECTORY_SEPARATOR.$pdf_name);//save pdf file

        return url('uploads/pdf/'.$pdf_name);//return pdf url
    }
}

if (!function_exists('print_barcode')) 
{  
    function print_barcode($group,$number,$barcode_image)
    {
        $pdf_name=time().'.pdf';

        $pdf = PDF::loadView('pdf.barcode',compact('group','number','barcode_image'));

        $pdf->save('uploads/pdf/'.$pdf_name);//save pdf file

        return url('uploads/pdf/'.$pdf_name);
    }
}

//check if report all subtests and cultures are done
if (!function_exists('check_group_done')) 
{  
    function check_group_done($group_id)
    {
        $group=Group::with(['tests','cultures'])->where('id',$group_id)->first();

        $done=true;

        if(isset($group))
        {
            //check tests
            foreach($group['tests'] as $test)
            {
                if(!$test['done'])
                {
                    $done=false;
                }

            }
            //check cultures
            foreach($group['cultures'] as $culture)
            {
                if(!$culture['done'])
                {
                    $done=false;
                }

            }
        }

        $group->update(['done'=>$done]);

        return $done;
    }
}

//group test calculations
if (!function_exists('group_test_calculations')) 
{
    function group_test_calculations($id)
    {
        $group=Group::with('tests','cultures','contract')->where('id',$id)->first();

        $subtotal=0;
        $discount=0;
        $paid=$group['paid'];
        $doctor_commission=0;

        if(isset($group['tests']))
        {
            foreach($group['tests'] as $test)
            {
                $subtotal+=$test['price'];
            }
        }

        if(isset($group['cultures']))
        {
            foreach($group['cultures'] as $culture)
            {
                $subtotal+=$culture['price'];
            }
        }

        if(isset($group['contract']))
        {
            $discount=($group['contract']['discount']*$subtotal)/100;
        }

        $total=$subtotal-$discount;
        $due=$total-$paid;

        if(isset($group['doctor']))
        {
            $doctor_commission=$total*$group['doctor']['commission']/100;
        }

        $group->update([
            'subtotal'=>$subtotal,
            'discount'=>$discount,
            'total'=>$subtotal-$discount,
            'paid'=>$paid,
            'due'=>$due,
            'doctor_commission'=>$doctor_commission
        ]);

    }
}

if (!function_exists('patient_code')) 
{
    function patient_code()
    {
        $code=time().mt_rand(1,1000);

        $patient=Patient::where('code',$code)->first();

        if(isset($patient))
        {
            patient_code();
        }

        return $code;
    }
}

if (!function_exists('doctor_code')) 
{
    function doctor_code()
    {
        $code=time().mt_rand(1,1000);

        $doctor=Doctor::where('code',$code)->first();

        if(isset($doctor))
        {
            doctor_code();
        }

        return $code;
    }
}

if (!function_exists('whatsapp_notification')) 
{
    function whatsapp_notification($group,$type)
    {
        $whatsapp=setting('whatsapp');

        if($type=='receipt')
        {
            $message=str_replace(['{patient_name}','{receipt_link}'],[$group['patient']['name'],$group['receipt_pdf']],$whatsapp['receipt']['message']);
            $url='https://wa.me/'.$group['patient']['phone'].'?text='.$message;

            return $url;
        }
        elseif($type=='report')
        {
            $message=str_replace(['{patient_name}','{report_link}'],[$group['patient']['name'],$group['report_pdf']],$whatsapp['report']['message']);
            $url='https://wa.me/'.$group['patient']['phone'].'?text='.$message;

            return $url;
        }

    }
}
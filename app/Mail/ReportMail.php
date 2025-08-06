<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Group;
use App\Models\Patient;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $patient;
    public $info;
    public $emails;
    public $body;
    public $group;
    public function __construct(Patient $patient,Group $group)
    {
        $this->patient=$patient;
        $this->group=$group;

        //info
        $this->info=setting('info');

        //patient code email
        $emails=setting('emails');
        $this->emails=$emails;

        //body
        $this->body=str_replace(['{patient_name}'],[$patient['name']],$emails['report']['body']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.report',[
            'body'=>$this->body,
            'emails'=>$this->emails,
            'info'=>$this->info
        ])
        ->subject($this->emails['report']['subject'])
        ->attach($this->group['report_pdf'])
        ->from($this->info['email'],'noreply');
    }
}

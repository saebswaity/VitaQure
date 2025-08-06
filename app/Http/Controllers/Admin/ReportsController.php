<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Test;
use App\Models\GroupTest;
use App\Models\GroupCulture;
use App\Models\GroupTestResult;
use App\Models\GroupCultureResult;
use App\Models\GroupCultureOption;
use App\Models\Antibiotic;
use App\Models\Setting;
use App\Models\Patient;
use App\Models\TestOption;
use App\Http\Requests\Admin\UpdateCultureResultRequest;
use App;
use DataTables;

class ReportsController extends Controller
{
    /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_report',     ['only' => ['index', 'show']]);
        $this->middleware('can:create_report',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_report',     ['only' => ['edit', 'update']]);
        $this->middleware('can:delete_report',   ['only' => ['destroy']]);
        $this->middleware('can:sign_report',   ['only' => ['sign']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * get groups datatable
     *
     * @access public
     * @var  @Request $request
     */
    public function ajax(Request $request)
    {
        $model = Group::query()->with('patient', 'tests', 'cultures')->orderBy('id', 'desc');

        if ($request['filter_status'] != null) {
            $model->where('done', $request['filter_status']);
        }

        if ($request['filter_barcode'] != null) {
            $model->where('barcode', $request['filter_barcode']);
        }

        if ($request['filter_date'] != null) {
            //format date
            $date = explode('-', $request['filter_date']);
            $from = date('Y-m-d', strtotime($date[0]));
            $to = date('Y-m-d 23:59:59', strtotime($date[1]));

            //select groups of date between
            ($from == $to) ? $model->whereDate('created_at', $from) : $model->whereBetween('created_at', [$from, $to]);
        }

        return DataTables::eloquent($model)
            ->editColumn('patient.gender', function ($group) {
                return __(ucwords($group['patient']['gender']));
            })
            ->editColumn('tests', function ($group) {
                return view('admin.reports._tests', compact('group'));
            })
            ->addColumn('signed', function ($group) {
                return view('admin.reports._signed', compact('group'));
            })
            ->editColumn('done', function ($group) {
                return view('admin.reports._status', compact('group'));
            })
            ->addColumn('action', function ($group) {
                return view('admin.reports._action', compact('group'));
            })
            ->toJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Group::findOrFail($id);

        return view('admin.reports.show', compact('group'));
    }

    /**
     * Generate report pdf
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request, $id)
    {
        //set null if no analysis or cultures selected
        if (empty($request['tests'])) {
            $request['tests'] = [-1];
        }
        if (empty($request['cultures'])) {
            $request['cultures'] = [-1];
        }

        //find group
        // $group = Group::with([
        //     'tests' => function ($q) use ($request) {
        //         return $q->whereIn('id', $request['tests']);
        //     },
        //     'cultures' => function ($q) use ($request) {
        //         return $q->whereIn('id', $request['cultures']);
        //     },
        //     'category'
        // ])->where('id', $id)->first();
        $group = Group::with([
        'tests' => function ($q) use ($request) {
            $q->whereIn('id', $request['tests'])->with('test.category');
        },
        'cultures' => function ($q) use ($request) {
            $q->whereIn('id', $request['cultures']);
        }
    ])->findOrFail($id);

        //generate pdf
        $pdf = generate_pdf($group);

        if (isset($pdf)) {
            return redirect($pdf);
        } else {
            session()->flash('failed', __('Something Went Wrong'));
            return redirect()->back();
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Group::with(['tests' => function ($q) {
            return $q->with('test.components');
        }, 'cultures'])->where('id', $id)->firstOrFail();

        $select_antibiotics = Antibiotic::all();

        return view('admin.reports.edit', compact('group', 'select_antibiotics'));
    }

    /**
     * Update analysis report
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'comment' => 'nullable|string',
            'result' => 'required|array',
        ]);

        // Retrieve the group test by ID
        $groupTest = GroupTest::findOrFail($id);

        // Initialize variables for MCV, MCH, MCHC
        $MCV = null;
        $MCH = null;
        $MCHC = null;

        // Update the group test with the comment and set 'done' to true
        $groupTest->update([
            'done' => true,
            'comment' => $request->input('comment'),
        ]);

        // Check if the group test is a CBC test
        $isCBC = $groupTest->test->name === 'CBC';

        // Loop through the result data and update each result
        foreach ($request->input('result') as $resultId => $resultData) {
            // Find the group test result by ID
            $groupTestResult = GroupTestResult::findOrFail($resultId);

            // Update the group test result with the new result data
            $groupTestResult->update([
                'result' => $resultData['result'],
                'status' => isset($resultData['status']) ? $resultData['status'] : null,
            ]);

            // Calculate MCV, MCH, MCHC if the result corresponds to RBC, HCT, Hb and the test is CBC
            if ($isCBC) {
                if ($groupTestResult->component->name == 'RBC') {
                    $RBC = $resultData['result'];
                } elseif ($groupTestResult->component->name == 'HCT') {
                    $HCT = $resultData['result'];
                } elseif ($groupTestResult->component->name == 'Hb') {
                    $Hb = $resultData['result'];
                }
            }
        }

        // Calculate MCV, MCH, MCHC if RBC, HCT, Hb are all set and the test is CBC
        if ($isCBC && isset($RBC) && isset($HCT) && isset($Hb)) {
            $MCV = round(($HCT * 10) / $RBC, 1);
            $MCH = round(($Hb * 10) / $RBC, 1);
            $MCHC = round(($Hb * 100) / $HCT, 1);
        }

        // Update or create GroupTestResult entries for MCV, MCH, MCHC only if the test is CBC
        if ($isCBC) {
            $this->updateOrInsertTestResult($groupTest->id, 'MCV', $MCV);
            $this->updateOrInsertTestResult($groupTest->id, 'MCH', $MCH);
            $this->updateOrInsertTestResult($groupTest->id, 'MCHC', $MCHC);
        }

        // Optionally, perform any additional actions or validations here

        // Redirect back with a success message
        return redirect()->back()->with('success', __('Test result saved successfully'));
    }

    private function updateOrInsertTestResult($groupTestId, $componentName, $result)
    {
        $test = Test::where('name', $componentName)->first();

        if ($test) {
            $groupTestResult = GroupTestResult::updateOrCreate(
                ['group_test_id' => $groupTestId, 'test_id' => $test->id],
                ['result' => $result]
            );
        }
    }
    /**
     * Update culture report
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update_culture(UpdateCultureResultRequest $request, $id)
    // {
    //     $group_culture = GroupCulture::findOrFail($id);

    //     GroupCultureResult::where('group_culture_id', $id)->delete();

    //     $group_culture->update([
    //         'done' => true,
    //         'comment' => $request['comment']
    //     ]);

    //     //save options
    //     if ($request->has('culture_options')) {
    //         foreach ($request['culture_options'] as $key => $value) {
    //             GroupCultureOption::where('id', $key)->update([
    //                 'value' => $value
    //             ]);
    //         }
    //     }

    //     //save antibiotics
    //     if ($request->has('antibiotic')) {
    //         foreach ($request['antibiotic'] as $antibiotic) {
    //             if (!empty($antibiotic['antibiotic']) && !empty($antibiotic['sensitivity'])) {
    //                 GroupCultureResult::create([
    //                     'group_culture_id' => $id,
    //                     'antibiotic_id' => $antibiotic['antibiotic'],
    //                     'sensitivity' => $antibiotic['sensitivity'],
    //                 ]);
    //             }
    //         }
    //     }


    //     //check if all reports done
    //     $done = check_group_done($group_culture['group_id']);

    //     //send tests notification
    //     $group = Group::find($group_culture['group_id']);
    //     if ($done) {
    //         $patient = Patient::find($group['patient_id']);
    //         send_notification('tests_notification', $patient);
    //     }
    //     //end check

    //     //generate pdf
    //     $pdf = generate_pdf($group);

    //     if (isset($pdf)) {
    //         $group->update(['report_pdf' => $pdf]);
    //     }

    //     session()->flash('success', __('Culture result saved successfully'));

    //     return redirect()->back();
    // }
// public function update_culture(UpdateCultureResultRequest $request, $id)
// {
//     // Retrieve group culture
//     $group_culture = GroupCulture::findOrFail($id);

//     // Delete existing group culture results
//     GroupCultureResult::where('group_culture_id', $id)->delete();

//     // Update group culture with comment
//     $group_culture->update([
//         'done' => true,
//         'comment' => $request['comment']
//     ]);

//     // Save options
//     if ($request->has('culture_options')) {
//         foreach ($request['culture_options'] as $key => $value) {
//             // Check if custom value exists
//             if ($value === 'other' && $request->has('culture_options_custom') && isset($request['culture_options_custom'][$key])) {
//                 $value = $request['culture_options_custom'][$key];
//             }

//             // Update or create group culture option
//             GroupCultureOption::updateOrCreate(
//                 ['id' => $key],
//                 ['value' => $value]
//             );
//         }
//     }

 
//      if ($request->has('antibiotic')) {
//             foreach ($request['antibiotic'] as $antibiotic) {
//                 if (!empty($antibiotic['antibiotic']) && !empty($antibiotic['sensitivity'])) {
//                     GroupCultureResult::create([
//                         'group_culture_id' => $id,
//                         'antibiotic_id' => $antibiotic['antibiotic'],
//                         'sensitivity' => $antibiotic['sensitivity'],
//                     ]);
//                 }
//             }
//         }

//     // Redirect back with success message
//     session()->flash('success', __('Culture result saved successfully'));

//     // Redirect back with updated values
//     return redirect()->back()->withInput($request->input());
// }
public function update_culture(UpdateCultureResultRequest $request, $id)
{
    // Retrieve group culture
    $group_culture = GroupCulture::findOrFail($id);

    // Delete existing group culture results
    GroupCultureResult::where('group_culture_id', $id)->delete();

    // Update group culture with comment
    $group_culture->update([
        'done' => true,
        'comment' => $request->input('comment')
    ]);

    // Save options
    if ($request->has('culture_options')) {
        foreach ($request['culture_options'] as $key => $value) {
            // Check if custom value exists
            if ($value === 'other' && $request->has('culture_options_custom') && isset($request['culture_options_custom'][$key])) {
                $value = $request['culture_options_custom'][$key];
            }

            // Update or create group culture option
            GroupCultureOption::updateOrCreate(
                ['id' => $key],
                ['value' => $value]
            );
        }
    }

    // Save antibiotics with sensitivity mappings
    
    if ($request->has('antibiotic')) {
        foreach ($request['antibiotic'] as $antibiotic) {
            if (!empty($antibiotic['antibiotic']) && !empty($antibiotic['sensitivity'])) {
                GroupCultureResult::create([
                    'group_culture_id' => $id,
                    'antibiotic_id' => $antibiotic['antibiotic'],
                    'sensitivity' => $antibiotic['sensitivity'],
                ]);
            }
        }
    }

    // Redirect back with success message
    session()->flash('success', __('Culture result saved successfully'));

    // Redirect back with updated values
    return redirect()->back()->withInput($request->input());
}
    /**public function update_culture(UpdateCultureResultRequest $request, $id)
{
    $group_culture = GroupCulture::findOrFail($id);

    GroupCultureResult::where('group_culture_id', $id)->delete();

    $group_culture->update([
        'done' => true,
        'comment' => $request['comment']
    ]);

    // Save options
    if ($request->has('culture_options')) {
        foreach ($request['culture_options'] as $key => $value) {
            GroupCultureOption::where('id', $key)->update([
                'value' => $value
            ]);
        }
    }

    // Save antibiotics with sensitivity mappings
    if ($request->has('antibiotic')) {
        foreach ($request['antibiotic'] as $antibiotic) {
            if (!empty($antibiotic['antibiotic']) && !empty($antibiotic['sensitivity'])) {
                // Map displayed sensitivity values to database values
                switch ($antibiotic['sensitivity']) {
                    case 'High':
                        $sensitivity = 'Sensitive';
                        break;
                    case 'Moderate':
                        $sensitivity = 'Intermediate';
                        break;
                    case 'Resistant':
                        $sensitivity = 'Resistant';
                        break;
                    default:
                        $sensitivity = $antibiotic['sensitivity'];
                        break;
                }

                GroupCultureResult::create([
                    'group_culture_id' => $id,
                    'antibiotic_id' => $antibiotic['antibiotic'],
                    'sensitivity' => $sensitivity,
                ]);
            }
        }
    }


    // Check if all reports are done
    $done = check_group_done($group_culture['group_id']);

    // Send tests notification
    $group = Group::find($group_culture['group_id']);
    if ($done) {
        $patient = Patient::find($group['patient_id']);
        send_notification('tests_notification', $patient);
    }
    // End check

    // Generate pdf
    $pdf = generate_pdf($group);

    if (isset($pdf)) {
        $group->update(['report_pdf' => $pdf]);
    }

    session()->flash('success', __('Culture result saved successfully'));

    return redirect()->back();
}
     * Sign report
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sign($id)
    {
        $group = Group::where('id', $id)->firstOrFail();

        //add signature
        $group->update([
            'signature' => auth()->guard('admin')->user()->signature
        ]);

        //generate pdf
        $pdf = generate_pdf($group);

        if (isset($pdf)) {
            $group->update(['report_pdf' => $pdf]);
        }

        session()->flash('success', __('Report signed successfully'));

        return redirect()->route('admin.reports.index');
    }

    /**
     * Send report
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function send_report_mail(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $patient = $group['patient'];

        send_notification('report', $patient, $group);

        session()->flash('success', __('Mail sent successfully'));

        return redirect()->route('admin.reports.index');
    }
}

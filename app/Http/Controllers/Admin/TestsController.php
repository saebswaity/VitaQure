<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\TestOption;
use App\Http\Requests\Admin\TestRequest;
use App\Models\CatogeryTests;
use App\Models\Culture;

use DataTables;

class TestsController extends Controller
{
     /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_test',     ['only' => ['index', 'show','ajax']]);
        $this->middleware('can:create_test',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_test',     ['only' => ['edit', 'update']]);
        $this->middleware('can:delete_test',   ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = CatogeryTests::all();
        return view('admin.tests.index', compact('categories'));
    }


    /**
    * get tests datatable
    *
    * @access public
    * @var  @Request $request
    */
    public function ajax(Request $request)
    {
        $filter = $request->get('filter');

        // Base query: top-level tests or separated components
        $query = Test::query()->where(function ($q) {
            $q->where('parent_id', 0)->orWhere('separated', true);
        });

        // Apply category filter if provided and not culture
        if ($filter && $filter !== 'culture') {
            $query->where('catogery_id', $filter);
        }

        // If culture selected, return cultures dataset
        if ($filter === 'culture') {
            $cultureQuery = Culture::query();

            return DataTables::eloquent($cultureQuery)
                ->addColumn('type', function () {
                    return 'Culture';
                })
                ->addColumn('shortcut', function () {
                    return '-';
                })
                ->editColumn('price', function ($culture) {
                    return formated_price($culture['price']);
                })
                ->addColumn('action', function ($culture) {
                    return view('admin.cultures._action', compact('culture'));
                })
                ->toJson();
        }

        return DataTables::eloquent($query)
            ->addColumn('type', function () {
                return 'Test';
            })
            ->editColumn('price', function ($test) {
                return formated_price($test['price']);
            })
            ->addColumn('action', function ($test) {
                return view('admin.tests._action', compact('test'));
            })
            ->toJson();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $catogeryTests = CatogeryTests::all();
        return view('admin.tests.create')->with('catogeryTests', $catogeryTests);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


       // return $request->all();
        $test=Test::create([
            'name'=>$request['name'],
            'shortcut'=>$request['shortcut'],
            'sample_type'=>$request['sample_type'],
            'price'=>$request['price'],
            'precautions'=>$request['precautions'],
            'catogery_id' => $request->input('category_id'),

            'parent_id'=>0,


        ]);

        //create components
        if($request->has('component'))
        {

            foreach($request->component as $component)
            {
                if(isset($component['title']))
                {
                    Test::create([
                        'parent_id'=>$test['id'],
                        'name'=>$component['name'],
                        'title'=>true,
                    ]);
                }
                else{
                    $test_component=Test::create([
                        'parent_id'=>$test['id'],
                        'type'=>$component['type'],
                        'name'=>$component['name'],
                        'unit'=>(isset($component['unit']))?$component['unit']:'',
                        'reference_range'=>(isset($component['reference_range']))?$component['reference_range']:'',
                        'title'=>(isset($component['title']))?true:false,
                        'separated'=>(isset($component['separated'])),
                        'price'=>(isset($component['price']))?$component['price']:0,
                        'status'=>(isset($component['status'])),
                        'sample_type'=>$test['sample_type']
                    ]);

                    //assign options to component
                    if(isset($component['options']))
                    {
                        foreach($component['options'] as $option)
                        {
                            TestOption::create([
                                'name'=>$option,
                                'test_id'=>$test_component['id']
                            ]);
                        }
                    }
                }
            }
        }

        session()->flash('success',__('Test created successfully'));

        return redirect()->route('admin.tests.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $catogeryTests = CatogeryTests::all();
        $test = Test::with('components')->where('id', $id)->firstOrFail();
        $categoryId = $test->catogery_id;
        return view('admin.tests.edit', compact('test', 'catogeryTests', 'categoryId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TestRequest $request, $id)
    {
        $test=Test::findOrFail($id);

        //update analysis basic info
        $test->update([
            'name'=>$request['name'],
            'shortcut'=>$request['shortcut'],
            'sample_type'=>$request['sample_type'],
            'price'=>$request['price'],
            'precautions'=>$request['precautions'],
            'catogery_id' => $request->input('category_id'),
            'parent_id'=>0
        ]);

        //components
        if($request->has('component'))
        {
            foreach($request->component as $component)
            {
                if(isset($component['title']))
                {
                    if(isset($component['id']))
                    {
                        Test::where('id',$component['id'])->update([
                            'name'=>$component['name'],
                        ]);
                    }
                    else{
                        Test::create([
                            'parent_id'=>$id,
                            'name'=>$component['name'],
                            'title'=>true,
                        ]);
                    }
                }
                else{
                    if(isset($component['id']))
                    {
                        $test_component=Test::where('id',$component['id'])->first();

                        $test_component->update([
                            'parent_id'=>$id,
                            'type'=>$component['type'],
                            'name'=>$component['name'],
                            'unit'=>(isset($component['unit']))?$component['unit']:'',
                            'reference_range'=>(isset($component['reference_range']))?$component['reference_range']:'',
                            'title'=>(isset($component['title']))?true:false,
                            'separated'=>(isset($component['separated'])),
                            'price'=>(isset($component['price']))?$component['price']:0,
                            'status'=>(isset($component['status'])),
                            'sample_type'=>$test['sample_type']
                        ]);

                        //delete options if not select type
                        if($component['type']!='select')
                        {
                            $test_component->options()->delete();
                        }

                        //update old options
                        if(isset($component['old_options']))
                        {
                            foreach($component['old_options'] as $option_id=>$option)
                            {
                                TestOption::where('id',$option_id)->update([
                                    'name'=>$option,
                                ]);
                            }
                        }

                        //assign options to component
                        if(isset($component['options']))
                        {
                            foreach($component['options'] as $option)
                            {
                                TestOption::create([
                                    'name'=>$option,
                                    'test_id'=>$test_component['id']
                                ]);
                            }
                        }
                    }
                    else{

                        $test_component=Test::create([
                            'parent_id'=>$id,
                            'type'=>$component['type'],
                            'name'=>$component['name'],
                            'unit'=>(isset($component['unit']))?$component['unit']:'',
                            'reference_range'=>(isset($component['reference_range']))?$component['reference_range']:'',
                            'title'=>(isset($component['title']))?true:false,
                            'separated'=>(isset($component['separated'])),
                            'price'=>(isset($component['price']))?$component['price']:0,
                            'status'=>(isset($component['status'])),
                            'sample_type'=>$test['sample_type']
                        ]);

                        //assign options to component
                        if(isset($component['options']))
                        {
                            foreach($component['options'] as $option)
                            {
                                TestOption::create([
                                    'name'=>$option,
                                    'test_id'=>$test_component['id']
                                ]);
                            }
                        }

                    }

                }
            }
        }

        session()->flash('success',__('Test updated successfully'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $test=Test::findOrFail($id);

       //delete old components
       $components=Test::where('parent_id',$id)->get();

       foreach($components as $component)
       {
           $component->options()->delete();
           $component->delete();
       }

        $test->options()->delete();

        $test->delete();

        session()->flash('success',__('Test deleted successfully'));

        return redirect()->route('admin.tests.index');
    }
}

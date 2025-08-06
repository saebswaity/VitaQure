<?php

namespace App\Http\Controllers\Admin;

use App\Models\Test;
use App\Http\Controllers\Controller;

use App\Models\CatogeryTests;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CatogeryRequest;



class CatogeryTestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catogeryTests = CatogeryTests::all();
        return view('admin.tests.catogery', compact('catogeryTests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tests.create_catogery');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

        //return $request->all();
        CatogeryTests::create([
            'catogery' => $request->catogery,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Catogery created successfully.');
    }
   /*  public function store(CatogeryRequest $request)
    {
        CatogeryTests::create([
            'catogery' => $request->catogery,
            'description' => $request->description,

        ]);

         return $request->all();

         session()->flash('success', __('Test created successfully'));

        return redirect()->route('admin.tests.index');
    }
 */
    /**
     * Display the specified resource.
     *
     * @param  \App\CatogeryTests  $catogeryTests
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $catogeryTests = CatogeryTests::all();
        return view('admin.tests.catogery', compact('catogeryTests'));
        return view('admin.tests.catogery');
    }


    public function destroy(CatogeryTests $catogeryTest)
    {
        $tests = $catogeryTest->tests()->get();

        foreach ($tests as $test) {
            $test->delete();
        }

        $catogeryTest->delete();

        return redirect()->back()->with('success', 'Category deleted successfully.');
    }



}

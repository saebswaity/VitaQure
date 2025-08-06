<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CacheController extends Controller
{
    public function clear()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        return redirect()->back()->with('success', 'Cache cleared successfully');
    }
} 
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CatogeryTestsController;
use App\Http\Controllers\Admin\TestsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CacheController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/zaid', [HomeController::class, 'welcome']);

Route::post('/posts/store', [CatogeryTestsController::class, 'store']);
Route::post('/test/store', [TestsController::class, 'store']);
Route::delete('/admin/catogery/{catogeryTest}', [CatogeryTestsController::class, 'destroy'])->name('admin.catogery.destroy');


/* Route::post('/store', 'CatogeryTestsController@store')->name('catogery.store');
 */

Route::group(['middleware'=>['Install','Locale']],function(){
  include('admin.php');
  include('ajax.php');
  include('patient.php');
});

Route::get('change_locale/{lang}','HomeController@change_locale')->name('change_locale');

Route::get('clear-cache', [CacheController::class, 'clear']);


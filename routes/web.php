<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CatogeryTestsController;
use App\Http\Controllers\Admin\TestsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CacheController;
use App\Http\Controllers\ChatbotProxyController;
use App\Http\Controllers\AIChatController;

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

// Proxy Chatbot (Flask) under /chatbot_langchain path
Route::any('/chatbot_langchain/{path?}', [ChatbotProxyController::class, 'handle'])
    ->where('path', '.*');

// Built-in AI chat (UI only under /ai-chat/embed)
Route::get('/ai-chat/embed', [AIChatController::class, 'index']);
Route::get('/ai-chat/api/models', [AIChatController::class, 'models']);
Route::post('/ai-chat/api/chat', [AIChatController::class, 'chat']);
Route::post('/ai-chat/api/clear', [AIChatController::class, 'clear']);


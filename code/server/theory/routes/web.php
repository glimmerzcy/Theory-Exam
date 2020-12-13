<?php
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
Route::get('/', 'MainPageController@welcome');

Route::get('/student', 'MainPageController@welcome');

Route::get('/college', 'MainPageController@welcome');

Auth::routes();

Route::get('/welcome', 'HomeController@index')->name('home');

Route::get('/admini','MainPageController@admin');

Route::middleware('auth')->namespace('Admin')->group(function () {
    Route::resource('admini/tests', 'TestController');
    Route::resource('admini/permissions', 'PermissionController');
    Route::resource('admini/notices', 'NoticeController');
    Route::resource('admini/others', 'OtherController');
});

<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//session
Route::get('/session', 'MainPageController@session');

//主页数据集合
Route::get('/main/page/v1', 'MainPageController@MainPage');

//学院登录
Route::post('/college/login/v1', 'College\LoginController@login');
Route::get('/college/login/status/v1', 'College\LoginController@loginStatus');
Route::get('/college/logout/v1', 'College\LoginController@logout');
Route::post('/college/password/reset/v1', 'College\LoginController@PasswordReset');

//后台试卷操作
Route::get('/college/papers/v1', 'College\GetPaperController@GetPapersHead');
Route::post('/college/common/paper/detail/v1', 'College\GetPaperController@GetPaperDetail');
Route::post('/college/common/paper/edit/v1', 'College\PaperEditController@edit');
Route::post('/college/makeup/paper/detail/v1', 'College\GetPaperController@GetMakeupPaperDetail');
Route::post('/college/makeup/paper/edit/v1', 'College\PaperEditController@MakeUpEdit');
Route::post('/college/paper/release/v1', 'College\ReleaseController@release');
Route::post('/college/paper/delete/v1', 'College\PaperEditController@DeletePaper');
Route::get('/college/subjective/record/export/v1', 'College\SubjectiveExportController@SubDownload');
Route::get('/colleges/v1', 'College\GetCollegesController@GetColleges');
Route::post('/score/export/v1','College\ScoreExportController@ScoreExport');

Route::get('/college/papers/v2', 'College\GetPaperController@GetPapersHead');
Route::post('/college/common/paper/head/edit/v2', 'College\PaperEditController@HeadEdit');
Route::get('/college/common/paper/model', 'College\PaperEditController@ModelExcelDownload');
Route::post('/college/common/paper/body/edit/v2', 'College\PaperEditController@BodyEdit');


//TwT单点登录
Route::get('/login', 'Student\LoginController@login');
Route::get('/loginStatus', 'Student\LoginController@loginStatus');
Route::get('/logout', 'Student\LoginController@logout');
Route::get('/storage', 'Student\LoginController@storage');
Route::get('/ssoLogin', 'Student\LoginController@ssoLogin');


//学生操作（部署完成再加入）
//Route::group(['middleware' => ['Auth']], function () {
Route::post('/student/test/draw/v1', 'Student\DrawQuestionsController@DrawQuestions');
Route::post('/student/test/score/v1', 'Student\ScoreController@ScoreCheck');
//});

<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('papers','PaperController');
    $router->resource('notices','NoticeController');
    $router->resource('permissions','PermissionController');
    $router->resource('students','StudentController');
    $router->resource('questions','QuestionController');
    $router->resource('scores','ScoreController');
    $router->resource('subjectives','SubjectiveController');

});

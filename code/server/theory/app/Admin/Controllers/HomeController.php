<?php

namespace App\Admin\Controllers;

use App\Admin\Metrics\Examples;
use App\Admin\Metrics\TestsCompletion;
use App\Admin\Metrics\TestsTaken;
use App\Admin\Metrics\TodayUsers;
use App\Admin\Metrics\Users;
use App\Http\Controllers\Controller;
use Dcat\Admin\Controllers\Dashboard;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('Dashboard')
            ->body(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $column->row(new TodayUsers());
                    $column->row(new Users());
                });

                $row->column(6, function (Column $column) {
                    $column->row(new TestsCompletion());
                    $column->row(new TestsTaken());
                });
            });
    }
}

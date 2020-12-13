<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\PaperDelete;
use App\Admin\Modals\Questions;
use App\Admin\Models\Question;
use App\Admin\Repositories\Paper;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class PaperController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Paper(), function (Grid $grid) {
            $grid->model()->where('is_exist',true)->orderBy('id','desc');

            $grid->name;
            $grid->college_code->label()->filter();
            $grid->twt_id->label()->filter();
            $grid->test_time;
            $grid->duration;
            $grid->started_at;
            $grid->ended_at;
            $grid->aim->label()->filter();
            $grid->related;
            $grid->tip->limit(10);
            $grid->status->label()->filter();

//            $grid->questions->display('试题查看')->modal('试题',Questions::make());

            $grid->quickSearch(function ($model, $query){
                $model->where('name','like','%'.$query.'%');
            });

            $grid->fixColumns(2,-2);
            $grid->disableDeleteButton();
            $grid->disableCreateButton();
            $grid->disableBatchActions();
            if (Admin::user()->isRole('administrator')){
                $grid->actions(new PaperDelete());
            }

        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Paper(), function (Show $show) {
            $show->name;
            $show->test_time;
            $show->duration;
            $show->started_at;
            $show->ended_at;
            $show->status;
            $show->tip;

            $show->disableDeleteButton();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Paper(), function (Form $form) {
            $form->text('name');
            $form->text('test_time');
            $form->text('duration');
            $form->datetime('started_at');
            $form->datetime('ended_at');
            $form->text('status');
            $form->textarea('tip');

            $form->disableDeleteButton();
        });
    }
}

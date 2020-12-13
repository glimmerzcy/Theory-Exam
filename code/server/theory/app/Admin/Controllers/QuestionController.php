<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\QuestionCleanUp;
use App\Admin\Actions\Grid\QuestionDelete;
use App\Admin\Actions\Grid\TagsAndQuestionsCleanUp;
use App\Admin\Repositories\Question;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class QuestionController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Question(), function (Grid $grid) {
            $grid->model()->where('is_exist',true)->orderBy('id','desc');

            $grid->topic->width('20%')->limit(15);
            $grid->objA->limit(15);
            $grid->objB->limit(15);
            $grid->objC->limit(15);
            $grid->objD->limit(15);
            $grid->objE->limit(15);
            $grid->objF->limit(15);
            $grid->answer;

            $grid->quickSearch(function ($model, $query){
                $model->where('topic','like','%'.$query.'%')
                    ->orWhere('objA','like','%'.$query.'%')
                    ->orWhere('objB','like','%'.$query.'%')
                    ->orWhere('objC','like','%'.$query.'%')
                    ->orWhere('objD','like','%'.$query.'%')
                    ->orWhere('objE','like','%'.$query.'%')
                    ->orWhere('objF','like','%'.$query.'%');
            });

            $grid->tools(new TagsAndQuestionsCleanUp());

            $grid->fixColumns(2,-2);
            $grid->disableDeleteButton();
            $grid->disableCreateButton();
            $grid->disableBatchActions();
            if (Admin::user()->isRole('administrator')){
                $grid->actions(new QuestionDelete());
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
        return Show::make($id, new Question(), function (Show $show) {
            $show->id;
            $show->topic;
            $show->objA;
            $show->objB;
            $show->objC;
            $show->objD;
            $show->objE;
            $show->objF;
            $show->answer;
            $show->created_at;
            $show->updated_at;
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Question(), function (Form $form) {
            $form->display('id');
            $form->text('topic');
            $form->text('objA');
            $form->text('objB');
            $form->text('objC');
            $form->text('objD');
            $form->text('objE');
            $form->text('objF');
            $form->text('answer');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}

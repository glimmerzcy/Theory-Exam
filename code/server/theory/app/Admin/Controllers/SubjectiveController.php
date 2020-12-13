<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\QuestionDelete;
use App\Admin\Actions\Grid\SubjectiveDelete;
use App\Admin\Actions\Grid\TagsAndQuestionsCleanUp;
use App\Admin\Repositories\Subjective;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class SubjectiveController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Subjective(), function (Grid $grid) {
            $grid->model()->where('is_exist',true)->orderBy('id','desc');

            $grid->column('topic')->limit(10);
            $grid->column('answer');

            $grid->quickSearch(function ($model, $query){
                $model->where('topic','like','%'.$query.'%')
                    ->orWhere('answer','like','%'.$query.'%');
            });

            $grid->tools(new TagsAndQuestionsCleanUp());

            $grid->disableDeleteButton();
            $grid->disableCreateButton();
            $grid->disableBatchActions();

            if (Admin::user()->isRole('administrator')){
                $grid->actions(new SubjectiveDelete());
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
        return Show::make($id, new Subjective(), function (Show $show) {
            $show->field('id');
            $show->field('topic');
            $show->field('answer');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Subjective(), function (Form $form) {
            $form->display('id');
            $form->text('topic');
            $form->text('answer');
        });
    }
}

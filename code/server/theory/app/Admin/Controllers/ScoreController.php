<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Score;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class ScoreController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Score(['student','paper']), function (Grid $grid) {
            $grid->column('student.stu_id');
            $grid->column('student.real_name');
            $grid->column('paper.name');
            $grid->column('time');
            $grid->column('score');

            $grid->quickSearch(function ($model, $query){
                $model->where('twt_id',$query);
            });

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
        return Show::make($id, new Score(), function (Show $show) {
            $show->field('id');
            $show->field('twt_id');
            $show->field('paper_id');
            $show->field('time');
            $show->field('score');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Score(), function (Form $form) {
            $form->display('id');
            $form->text('twt_id');
            $form->text('paper_id');
            $form->text('time');
            $form->text('score');
        });
    }
}

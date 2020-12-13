<?php

namespace App\Admin\Controllers;

use App\Admin\Modals\Scores;
use App\Admin\Repositories\Student;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class StudentController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Student(), function (Grid $grid) {
            $grid->model()->orderBy('twt_id','desc');

            $grid->twt_id;
            $grid->stu_id->sortable();
            $grid->real_name;
            $grid->academic->width('150px');
            $grid->profession->filter(
                Grid\Column\Filter\Like::make()
            )->width('150px');
            $grid->grade->label()->filter();
            $grid->gender->label()->filter();
            $grid->province->label()->filter();
            $grid->成绩->display('成绩')->expand(Scores::make());

            $grid->quickSearch(function ($model, $query){
                $model->where('stu_id',$query)
                    ->orWhere('real_name','like','%'.$query.'%')
                    ->orWhere('academic','like','%'.$query.'%')
                    ->orWhere('profession','like','%'.$query.'%')
                    ->orWhere('grade',$query);
            });

            $grid->disableDeleteButton();
            $grid->disableActions();
            $grid->disableCreateButton();
            $grid->disableBatchActions();
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
        return Show::make($id, new Student(), function (Show $show) {
            $show->twt_id;
            $show->real_name;
            $show->stu_id;
            $show->academic;
            $show->profession;
            $show->grade;
            $show->class;
            $show->gender;
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Student(), function (Form $form) {
            $form->text('twt_id');
            $form->text('real_name');
            $form->text('stu_id');
            $form->text('academic');
            $form->text('profession');
            $form->text('grade');
            $form->text('class');
            $form->text('gender');
        });
    }
}

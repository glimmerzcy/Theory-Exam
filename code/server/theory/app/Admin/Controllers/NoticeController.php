<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\NoticeDelete;
use App\Admin\Repositories\Notice;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class NoticeController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Notice(), function (Grid $grid) {
            $grid->model()->where('is_exist',true)->orderBy('id','desc');

            $grid->title->width('25%');
            $grid->content->limit(25);
            $grid->published_at;

            $grid->quickSearch(function ($model,$query){
                $model->where('title','like','%'.$query.'%')
                    ->orWhere('content','like','%'.$query.'%');
            });

            $grid->disableDeleteButton();
            $grid->disableBatchActions();
            if (Admin::user()->isRole('administrator')){
                $grid->actions(new NoticeDelete());
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
        return Show::make($id, new Notice(), function (Show $show) {
            $show->title;
            $show->content;
            $show->published_at;
            $show->is_exist;

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
        return Form::make(new Notice(), function (Form $form) {
            $form->text('title');
//            $form->textarea('content');
            $form->markdown('content');
            $form->datetime('published_at');

            $form->disableDeleteButton();
        });
    }
}

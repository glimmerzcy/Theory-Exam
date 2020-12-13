<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\PermissionRegain;
use App\Admin\Forms\StuIdForPermit;
use App\Admin\Repositories\Permission;
use App\Admin\Models\Permission as Per;
use Dcat\Admin\Admin;
use Dcat\Admin\Controllers\Dashboard;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\IFrameGrid;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Widgets\Card;

class PermissionController extends AdminController
{

    public function index(Content $content)
    {
        if (request(IFrameGrid::QUERY_NAME)) {
            return $content->perfectScrollbar()->body($this->iFrameGrid());
        }

        return $content
            ->title($this->title())
            ->description($this->description()['index'] ?? trans('admin.list'))
            ->body(function (Row $row){
                $row->column(8, function (Column $column) {
                    $column->row($this->grid());
                });

                $row->column(4, function (Column $column) {
                    $column->row(new StuIdForPermit());
                });
            });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Permission(['student']), function (Grid $grid) {
            $grid->model()->where('activated',true);

            $grid->column('student.stu_id');
            $grid->column('student.real_name');
            $grid->column('student.academic');
            $grid->column('student.profession');

            $grid->disableDeleteButton();
            $grid->disableBatchActions();
            $grid->disableFilterButton();
            $grid->disableViewButton();
            $grid->disableEditButton();
            $grid->disableCreateButton();
            if(!Admin::user()->isRole('visitor')){
                $grid->actions(new PermissionRegain());
            }

            $grid->quickSearch(function ($model, $query){
                $model->join('students','students.twt_id','=','permissions.twt_id')
                    ->where('students.stu_id',$query)
                    ->orWhere('students.real_name','like','%'.$query.'%')
                    ->orWhere('students.academic','like','%'.$query.'%')
                    ->orWhere('students.profession','like','%'.$query.'%')
                    ->orWhere('permissions.twt_id',$query);
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
        return Show::make($id, new Permission(),function (Show $show) {
            $show->twt_id;
            $show->activated;

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
        return Form::make(new Permission(), function (Form $form) {
            $form->text('twt_id');
            $form->text('activated');

            $form->disableDeleteButton();
        });
    }
}

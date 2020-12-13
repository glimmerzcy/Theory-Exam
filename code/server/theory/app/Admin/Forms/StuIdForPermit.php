<?php

namespace App\Admin\Forms;

use App\Admin\Models\Permission;
use App\Admin\Models\Student;
use Dcat\Admin\Widgets\Form;
use Symfony\Component\HttpFoundation\Response;

class StuIdForPermit extends Form
{
    /**
     * Handle the form request.
     *
     * @param array $input
     *
     * @return Response
     */
    public function handle(array $input)
    {
        // dump($input);

        // return $this->error('Your error message.');
        $stu_id = $input['授权'] ?? null;
        if (!$stu_id){
            return $this->error('参数错误');
        }
        $student = Student::query()->where('stu_id',$stu_id)->first();
        if(!$student){
            return $this->error('用户不存在');
        }

        $twt_id = $student->twt_id;
        $per = Permission::query()->where('twt_id',$twt_id)->first();
        if (!$per){
            $per = new Permission();
            $per->twt_id = $twt_id;
            $per->save();
        }else{
            $per->activated = true;
            $per->save();
        }

        return $this->success('Granted successfully.', '/permissions');
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->text('授权')->placeholder('请输入学号');
    }

}

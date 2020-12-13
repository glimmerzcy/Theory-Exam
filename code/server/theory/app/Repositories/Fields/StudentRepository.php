<?php


namespace App\Repositories\Fields;

use App\Models\Student;
use App\Repositories\BaseRepository;

class StudentRepository extends BaseRepository
{

    protected $model;

    public function __construct(Student $student){
        $this->model = $student;
    }

    public function SelByTwtId($twt_id){
        $where = array(['twt_id',$twt_id]);
        return $this->getSingleRecord($where,false);
    }

    public function RecordLink($records){
        foreach ($records as $record){
            $stu = $this->SelByTwtId($record['twt_id']);
            $record['real_name'] = $stu['real_name'];
            $record['stu_id'] = substr_replace(
                $stu['stu_id'],
                '****',
                3,
                4);
        }
        return $records;
    }

}

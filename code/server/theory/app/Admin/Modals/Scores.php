<?php

namespace App\Admin\Modals;

use App\Admin\Models\Student;
use App\Models\Paper;
use App\Models\Score;
use Dcat\Admin\Support\LazyRenderable;
use Dcat\Admin\Widgets\Table;

class Scores extends LazyRenderable{

    public function render()
    {
        // TODO: Implement render() method.
        $id = $this->key;
        $stu = Student::query()->find($id);

        $datas = Score::where("twt_id",$stu->twt_id)
            ->get(['paper_id','score'])
            ->toArray();

        $res = array();
        if ($datas != null){
            foreach ($datas as $key=>$data){
                $paper = Paper::query()->find((int)$data['paper_id']);
                if ($paper != null && $data['score'] != null){
                    $tmp = array(
                        "name"=>$paper->name,
                        "score"=>$data['score']
                    );
                    array_push($res,$tmp);
                }
            }
        }

        $titles = [
            '考试',
            '成绩'
        ];

        return Table::make($titles,$res);
    }
}

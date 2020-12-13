<?php


namespace App\Repositories\Fields;


use App\Models\Paper;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;

class PaperRepository extends BaseRepository
{

    protected $model,$tagRepository,$scoreRepository;

    public function __construct(
        Paper $paper,
        TagRepository $tagRepository,
        ScoreRepository $scoreRepository
    ){
        $this->model = $paper;
        $this->tagRepository = $tagRepository;
        $this->scoreRepository = $scoreRepository;
    }

    /**
     * 试卷状态更新
     */
    public function PaperStatusVerify(){
        if (!Cache::has('paperStatus')) {
            $where = array(
                ['status','已发布']
            );
            $input = array('status'=>'已结束');
            $this->model->where($where)
                ->whereDate('ended_at','<=',date('Y-m-d H:i:s'))
                ->update($input);
            Cache::add('paperStatus', 'verified', 60);
        }
    }

    /**
     * 已发布所有试卷获取
     * @return mixed
     */
    public function Released(){
        if (Cache::has('allTests')) {
            $papers = json_decode(Cache::get('allTests'));
        } else {
            $where = array(['status', '已发布'],['is_exist', true]);
            $orderBy = array(['started_at','desc']);
            $papers = $this->search($where,null,$orderBy);

            foreach ($papers as $paper) {
                $split = explode(" ", $paper->started_at);
                $paper->published_date = $split[0];
            }
            Cache::add('allTests', json_encode($papers), 43200);
        }
        return $papers;
    }

    /**
     * 获取试卷基本信息
     * @param $twt_id
     * @return mixed
     */
    public function GetTests($twt_id){
        $where = array(['is_exist',1],['twt_id',$twt_id]);
        return $this->search($where);
    }

    /**
     * 补考试卷处理
     * @param $papers
     * @param $grade
     * @return array
     */
    public function MakeUpFilter($papers, $grade)
    {
        $papers_final = array();
        foreach ($papers as $paper) {
            $paper_id2 = $paper->related;
            $paper = $this->search([['id',$paper_id2]]);
            if (isset($paper->started_at)) {
                $original_started_date = $paper->started_at;
                if (strtotime($original_started_date) >= strtotime($grade . '-09-01 00:00:00')) {
                    array_push($papers_final, $paper);
                }
            }
        }
        return $papers_final;
    }

    /**
     * 正常试卷处理
     * @param $papers
     * @param $twt_id
     * @return mixed
     */
    public function CommonTestProcess($papers, $twt_id)
    {
        foreach ($papers as $paper) {
            $paper_id = $paper->id;
            $record = $this->scoreRepository->check($twt_id, $paper_id);
            if ($record == null) {
                $paper->tested_time = 0;
                $paper->score = '无数据';
            } else {
                $paper->tested_time = $record->time;
                if ($record->score == null){
                    $paper->score = '无数据';
                }else{
                    $paper->score = $record->score;
                }
            }
        }
        return $papers;
    }

    /**
     * 试卷信息收集
     * @param $paper
     * @param $head
     * @param $twt_id
     * @return mixed
     */
    public function PaperHead($paper, $head, $twt_id)
    {
        $paper['name'] = $head['name'];
        $paper['test_time'] = $head['test_time'];
        $paper['twt_id'] = $twt_id;
        $paper['duration'] = $head['duration'];
        $paper['related'] = $head['related'];
        $paper['aim'] = $head['aim'];
        $paper['started_at'] = $head['started_at'];
        $paper['ended_at'] = $head['ended_at'];
        $paper['updated_at'] = date('Y-m-d H:i:s');
        $paper['tip'] = $head['tip'];
        return $paper;
    }

    /**
     * 正常试卷头增删改
     * @param $head
     * @param $twt_id
     * @return mixed
     */
    public function PaperHeadProcess($head, $twt_id)
    {
        if (isset($head['id'])) {
            $id = $head['id'];
            $paper = $this->getById($id);
            $this->PaperHead($paper, $head, $twt_id);
            $paper->save();
            return $id;
        } else {
            $paper['created_at'] = date('Y-m-d H:i:s');
            $paper = $this->PaperHead($paper, $head, $twt_id);
            $id = $this->model->insertGetId($paper);
            return $id;
        }
    }

    /**
     * 补考试卷头增删改
     * @param $head
     * @param $twt_id
     * @return mixed
     */
    public function MakeUpPaperHeadProcess($head, $twt_id)
    {
        if (isset($head['id'])) {
            $id = $head['id'];
            $paper = $this->getById($id);
            $this->PaperHead($paper, $head, $twt_id);
            $paper->save();
        } else {
            $paper['created_at'] = date('Y-m-d H:i:s');
            $paper = $this->PaperHead($paper, $head, $twt_id);
            $id = $this->model->insertGetId($paper);
        }
        $related = $head['related'];
        $where = array(['paper', $related]);
        $input = array('paper' => $id);
        $this->tagRepository->batchUpdate($where,$input);

        return $id;
    }

    /**删除试卷
     * @param $paper_id
     */
    public function DeletePaper($paper_id){
        $input = array('is_exist'=>0);
        $this->update($paper_id,$input);
    }

}

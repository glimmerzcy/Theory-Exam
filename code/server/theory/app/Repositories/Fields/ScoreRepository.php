<?php


namespace App\Repositories\Fields;

use App\Models\Question;
use App\Models\Score;
use App\Supports\Student;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class ScoreRepository extends BaseRepository
{

    protected $model,$studentRepository,$tagRepository,$question;

    public function __construct(
        Score $score,
        StudentRepository $studentRepository,
        TagRepository $tagRepository,
        Question $question
    )
    {
        $this->model = $score;
        $this->studentRepository = $studentRepository;
        $this->tagRepository = $tagRepository;
        $this->question = $question;
    }

    public function earlier($paper_id){
        $where = array(
            ['paper_id', $paper_id],
            ['score', '>=', 60],
            ['updated_at','!=',null]
        );
        $orderBy = array(['updated_at']);
        $select = array('twt_id', 'score', 'updated_at');
        return $this->search($where,null,$orderBy,30,$select);
    }

    public function higher($paper_id){
        $where = array(
            ['paper_id',$paper_id],
            ['time',1],
            ['updated_at','!=',null],
            ['score', '>=', 60]
        );
        $orderBy = array(
            ['score', 'desc'],
            ['updated_at']
        );
        $select = array('twt_id', 'score');
        return $this->search($where,null,$orderBy,30,$select);
    }

    public function allRank($paper_id){
        if (Cache::has('ranks')) {
            $records = json_decode(Cache::get('ranks'));
        } else {
            $records = array(
                'rankByTime'=> $this->studentRepository->RecordLink($this->earlier($paper_id)),
                'rankByScore'=>$this->studentRepository->RecordLink($this->higher($paper_id))
            );
            Cache::add('ranks', json_encode($records), 60);
        }
        return $records;
    }

    public function check($twt_id, $paper_id)
    {
        $where = array(
            ['twt_id', $twt_id],
            ['paper_id', $paper_id]
        );
        return $this->getSingleRecord($where,false);
    }

    public function Calculate($records){
        $score = 0;
        foreach ($records as $record) {
            $tag_id = $record['tag_id'];
            $where = array(['id', $tag_id]);
            $credit = $this->tagRepository->getSingleRecord($where,false)->credit_per_question;
            $answers = $record['answers'];
            [$ids,$ans] = Arr::divide($answers);
            $correct_ans = $this->question->whereIn('id',$ids)->select('id','answer')->get();
            for ($i=0;$i<count($ans);$i++){
                if (Student::AnswerStrToNum($ans[$i]) == $correct_ans[$i]->answer){
                    $score += $credit;
                }
            }
        }
        return $score;
    }

    public function ScoreRecord($twt_id, $paper_id, $score, $user_agent)
    {
        $where = array(
            'twt_id'=>$twt_id,
            'paper_id'=>$paper_id,
        );
        $data = $this->getSingleRecord($where,false);
        if ($data['score'] < $score or $data['score'] == null) {
            $data['score'] = $score;
        }
        $data['time'] = $data['time'] + 1;
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['ip'] = Student::getIP();
        $data['user_agent'] = $user_agent;
        $data->save();

        return array('time'=>$data['time'],'started_at'=>$data['started_at']);
    }
}

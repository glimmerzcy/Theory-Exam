<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Paper extends Model
{
    protected $fillable = [
        'name', 'college_code', 'test_time', 'duration', 'started_at',
        'ended_time', 'is_exist', 'created_at', 'updated_at', 'status',
        'aim', 'related'
    ];

    public function tags()
    {
        return $this->hasMany(
            'App\Models\Tag',
            'paper'
        );
    }

    public function questions()
    {
        return $this->hasManyThrough(
            'App\Models\Question',
            'App\Models\Tag',
            'paper',
            'tag'
        );
    }

    public function students()
    {
        return $this->belongsToMany(
            'App\Student',
            'paper_student',
            'paper_id',
            'twt_id'
        );
    }

    /**
     * (学院登录状态)试卷基本信息获取
     * @param $college_code
     * @return mixed
     */
    public static function GetTests($college_code)
    {
        return Paper::where('is_exist', 1)
            ->where('college_code', $college_code)
            ->get();
    }

    public static function GetTests2($twt_id)
    {
        return Paper::where('is_exist', 1)
            ->where('twt_id', $twt_id)
            ->get();
    }

    /**
     * (学生登录状态)补考试卷筛选
     * @param $papers
     * @return array
     */
    public static function MakeUpFilter($papers, $grade)
    {
        $papers_final = array();
        foreach ($papers as $paper) {
            $paper_id2 = $paper->related;
            $paper = Paper::find($paper_id2);
            if (isset(Paper::find($paper_id2)->started_at)) {
                $original_started_date = Paper::find($paper_id2)->started_at;
                if (strtotime($original_started_date) >= strtotime($grade . '-09-01 00:00:00')) {
                    array_push($papers_final, $paper);
                }
            }
        }
        return $papers_final;
    }

    /**
     * (学生登录状态)正常考试数据处理
     * @param $papers
     * @param $twt_id
     * @return mixed
     */
    public static function CommonTestProcess($papers, $twt_id)
    {
        foreach ($papers as $paper) {
            $paper_id = $paper->id;
            $record = Score::check($twt_id, $paper_id);
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
     * (学生登录状态)补考数据处理
     * @param $papers
     * @param $twt_id
     * @return mixed
     */
    public static function MakeupTestProcess($papers, $twt_id)
    {
        foreach ($papers as $paper) {
            $paper_id1 = $paper->related;
            $record1 = Score::check($twt_id, $paper_id1);
            $paper_id2 = $paper->id;
            $record2 = Score::check($twt_id, $paper_id2);
            if ($record1 == null || $record1->score < 60) {
                if ($record2 == null) {
                    $paper->tested_time = 0;
                    $paper->score = '无数据';
                } else {
                    $paper->tested_time = $record2->time;
                    $paper->score = $record2->score;
                    if ($paper->test_time == $paper->tested_time) {
                        $paper->status = '已结束';
                    }
                }
            } else {
                $paper->tested_time = $paper->test_time;
                $paper->score = $record1->score;
                $paper->status = '已结束';
            }
        }
        return $papers;
    }

    /**
     * (学生登录状态)校级正常考试获取
     * @param $twt_id
     * @return mixed
     */
    public static function GetUniTestCommon($twt_id,$grade)
    {
        $papers = Paper::where('is_exist', true)
            ->whereDate('started_at', '>=', $grade . '-09-01')
            ->where('is_exist', true)
            ->where('related', 0)
            ->where('aim', 0)
            ->whereIn('status', ['已发布', '已结束'])
            ->get();
        return self::CommonTestProcess($papers, $twt_id);
    }

    /**
     * (学生登录状态)院级正常考试获取
     * @param $twt_id
     * @return mixed
     */
    public static function GetAcademyTestCommon($twt_id,$grade,$college_code)
    {
        $paper_ids = DB::table('paper_college')
            ->where('college_code', '' . $college_code)
            ->select('paper_id')
            ->get();
        if ($paper_ids == null) {
            return null;
        } else {
            $paperIds = array();
            foreach ($paper_ids as $paper_id) {
                $paper = Paper::find($paper_id->paper_id);
                if (isset($paper['started_at'])) {
                    if (strtotime($paper['started_at']) >= strtotime($grade . '/09/01')) {
                        array_push($paperIds, $paper_id->paper_id);
                    }
                }
            }
            $papers = Paper::where('is_exist', true)
                ->where('is_exist', true)
                ->where('related', 0)
                ->where('aim', 1)
                ->where('college_code', '' . $college_code . '')
                ->whereIn('status', ['已发布', '已结束'])
                ->get();
            return self::CommonTestProcess($papers, $twt_id);
        }
    }

    /**
     * (学生登录状态)小型正常考试获取
     * @param $twt_id
     * @return mixed
     */
    public static function GetSmallTestCommon($twt_id)
    {
        $paper_ids = DB::table('paper_student')
            ->where('twt_id', '' . $twt_id)
            ->select('paper_id')
            ->get();
        $paperIds = array();
        foreach ($paper_ids as $paper_id) {
            array_push($paperIds, '' . $paper_id->paper_id);
        }
        $papers = Paper::whereIn('id', $paperIds)
            ->where('related', 0)
            ->where('is_exist', true)
            ->whereIn('status', ['已发布', '已结束'])
            ->get();
        return self::CommonTestProcess($papers, $twt_id);
    }

    /**
     * 试卷基本信息数据处理
     * @param $paper
     * @param $head
     * @param $twt_id
     * @return mixed
     */
    public static function PaperHead($paper, $head, $twt_id)
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

    public static function PaperHeadOld($paper, $head, $college_code)
    {
        $paper['name'] = $head['name'];
        $paper['test_time'] = $head['test_time'];
        $paper['college_code'] = $college_code;
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
     * 试卷基本信息增删改
     * @param $head
     * @param $twt_id
     * @return int
     */
    public static function PaperHeadProcess($head, $twt_id)
    {
        if (isset($head['id'])) {
            $id = $head['id'];
            $paper = Paper::find($id);
            self::PaperHead($paper, $head, $twt_id);
            $paper->save();
            return $id;
        } else {
            $paper['created_at'] = date('Y-m-d H:i:s');
            $paper = self::PaperHead($paper, $head, $twt_id);
            $id = Paper::insertGetId($paper);
            return $id;
        }
    }

    public static function PaperHeadProcessOld($head, $college_code)
    {
        if (isset($head['id'])) {
            $id = $head['id'];
            $paper = Paper::find($id);
            self::PaperHeadOld($paper, $head, $college_code);
            $paper->save();
            return $id;
        } else {
            $paper['created_at'] = date('Y-m-d H:i:s');
            $paper = self::PaperHeadOld($paper, $head, $college_code);
            $id = Paper::insertGetId($paper);
            return $id;
        }
    }

    public static function MakeUpPaperHeadProcess($head, $college_code)
    {
        if (isset($head['id'])) {
            $id = $head['id'];
            $paper = Paper::find($id);
            self::PaperHead($paper, $head, $college_code);
            $paper->save();
        } else {
            $paper['created_at'] = date('Y-m-d H:i:s');
            $paper = self::PaperHead($paper, $head, $college_code);
            Paper::insert($paper);
            $id = Paper::where('name', $paper['name'])
                ->where('college_code', $paper['college_code'])
                ->where('created_at', $paper['created_at'])
                ->first()
                ->id;
        }
        $related = $head['related'];
        Tag::where('paper', $related)
            ->update([
                'paper' => $id
            ]);
        return $id;
    }

    /**
     * 试卷详细信息增删改
     * @param $body
     * @param $paper_id
     * @return bool
     */
    public static function PaperBodyProcess($body, $paper_id)
    {
        $paper = Paper::find($paper_id);
        $has_sub = false;
        foreach ($body as $part) {
            $tag_id = Tag::OneTagProcess($part, $paper_id);
            $is_subjective = Tag::find($tag_id)->is_subjective;
            $questions = $part['questions'];
            if ($is_subjective == true) {
                $has_sub = true;
                foreach ($questions as $ques) {
                    Subjective::OneQuestionProcess($ques, $tag_id);
                }
            } else {
                foreach ($questions as $ques) {
                    Question::OneQuestionProcess($ques, $tag_id);
                }
            }
        }
        $paper->has_sub = $has_sub;
        $paper->save();
    }

    /**
     * 校级试卷发布
     * @param $paper_id
     * @return mixed
     */
    public static function UniPaperRelease($paper_id)
    {
        $paper = Paper::find($paper_id);
        $paper->status = '已发布';
        $paper->save();
        return Paper::find($paper_id)->status;
    }

    /**
     * 院级试卷发布
     * @param $paper_id
     * @param $college_codes
     * @return mixed
     */
    public static function ColPaperRelease($paper_id, $college_codes)
    {
        $exception['failed_college'] = array();
        $exception['failed_count'] = 0;
        foreach ($college_codes as $college_code) {
            DB::table('paper_college')->updateOrInsert(
                ['paper_id' => $paper_id, 'college_code' => $college_code],
                ['created_at' => date('Y-m-d H:i:s')]
            );
            $rec = DB::table('paper_college')->where('paper_id', $paper_id)->where('college_code', $college_code)->count();
            if ($rec == 0) {
                array_push($exception['failed_college'], $college_code);
                $exception['failed_count']++;
            }
        }
        if ($exception['failed_count'] == 0) {
            $paper = Paper::find($paper_id);
            $paper->status = '已发布';
            $paper->save();
        }
        return $exception;
    }

    /**
     * 小型考试发布
     * @param $paper_id
     * @param $twt_ids
     * @return bool
     */
    public static function SmallPaperRelease($paper_id, $twt_ids)
    {
        $exception['failed_twt_ids'] = array();
        $exception['failed_count'] = 0;
        foreach ($twt_ids as $twt_id) {
            DB::table('paper_student')->updateOrInsert(
                ['paper_id' => $paper_id, 'twt_id' => $twt_id],
                ['created_at' => date('Y-m-d H:i:s')]
            );
            $res = DB::table('paper_student')->where('paper_id', $paper_id)->where('twt_id', $twt_id)->count();
            if ($res == 0) {
                array_push($exception['failed_twt_ids'], $twt_id);
                $exception['failed_count']++;
            }
        }
        if ($exception['failed_count'] == 0) {
            $paper = Paper::find($paper_id);
            $paper->status = '已发布';
            $paper->save();
        } else {
            return $exception;
        }
    }

    /**
     * 删除试卷
     * @param $paper_id
     */
    public static function DeletePaper($paper_id)
    {
        $paper = Paper::find($paper_id);
        $paper->is_exist = 0;
        $paper->save();
    }

    /**
     * 判断是否为补考
     * @param $paper_id
     * @return mixed
     */
    public static function MakeupSwitch($paper_id)
    {
        $paper = Paper::find($paper_id);
        if (isset($paper['related'])) {
            $paper_id = $paper['related'] == 0?$paper_id:$paper['related'];

        }
        return $paper_id;
    }

    /**
     * 题组规范
     * @param $tag
     * @param $questions
     * @return mixed
     */
    public static function SortOut($tag, $questions)
    {
        $group['tag_id'] = $tag['id'];
        $group['tag_name'] = $tag['name'];
        $group['is_subjective'] = $tag['is_subjective'];
        $group['question'] = $questions;
        return $group;
    }

    /**
     * 学生端考试整合
     * @param $twt_id
     * @return array
     */
    public static function TestsIntegrate($twt_id,$grade,$college_code)
    {
        $papers = array();
        $papers00 = self::GetUniTestCommon($twt_id,$grade);
        foreach ($papers00 as $paper) {
            $papers['' . $paper->id . ''] = $paper;
            if (($paper->score == '无数据' or $paper->score < 60) and $paper->status == '已结束'){
                $paper2 = Paper::where('related',$paper->id)
                    ->whereIn('status', ['已发布', '已结束'])
                    ->first();
                if (isset($paper2['id'])){
                    $id = $paper2['id'];
                    $record = Score::where('paper_id',$id)->where('twt_id',$twt_id)->first();
                    if ($record!=null){
                        $paper2['tested_time'] = $record->time;
                        $paper2['score'] = $record->score == null?'无数据':$record->score;
                    }else{
                        $paper2['tested_time'] = 0;
                        $paper2['score'] = '无数据';
                    }
                    $papers[''.$id.''] = $paper2;
                }
            }
        }
        $papers10 = self::GetAcademyTestCommon($twt_id,$grade,$college_code);
        foreach ($papers10 as $paper) {
            $papers['' . $paper->id . ''] = $paper;
        }
        $papers20 = self::GetSmallTestCommon($twt_id);
        foreach ($papers20 as $paper) {
            $papers['' . $paper->id . ''] = $paper;
        }
        return $papers;
    }

    /**
     * 抽题核心
     * @param $paper_id
     * @param $twt_id
     * @param $has_sub
     * @return array
     */
    public static function draw($paper_id, $twt_id, $has_sub)
    {
        $paper_id2 = $paper_id;
        $paper_id = Paper::MakeupSwitch($paper_id);
        $rand = mt_rand(0,9);
        if (Cache::has($paper_id.$rand)) {
            Score::record($twt_id, $paper_id);
            if ($has_sub == true) {
                Subjective::record($twt_id, $paper_id);
            }
            return json_decode(Cache::get($paper_id.$rand));
        } else {
            $questions = array();
            $paper = array();
            $total_num = 0;
            $duration = Paper::find($paper_id)->duration;
            $tags = Tag::GetTagsByPaperId($paper_id);
            foreach ($tags as $tag) {
                $total_num += $tag['draw_quantity'];
                if ($tag['is_subjective'] == true) {
                    $questionsTmp = Subjective::GetRandomQuestionsByTagId($tag['id'], $tag['draw_quantity']);
                } else {
                    $questionsTmp = Question::GetRandomQuestionsByTagId($tag['id'], $tag['draw_quantity']);
                }
                array_push($questions, self::SortOut($tag, $questionsTmp));
            }
            Score::record($twt_id, $paper_id2);
            if ($has_sub == true) {
                Subjective::record($twt_id, $paper_id2);
            }
            $paper['paper_id'] = $paper_id2;
            $paper['total_number'] = $total_num;
            $paper['questions'] = $questions;
            $paper['duration'] = $duration;
            Cache::add($paper_id.$rand, json_encode($paper), 60);
            return $paper;
        }
    }

    /**
     * 指定试卷主观题考试记录获取
     * @param $paper_id
     * @return mixed
     */
    public static function subRecordGet($paper_id)
    {
        $records = DB::table('sub_records')
            ->where('paper_id', $paper_id)
            ->select('twt_id', 'record')
            ->get();
        foreach ($records as $record) {
            Student::ExportLink($record);
            $record->record = json_decode($record->record);
        }
        return $records;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\ZipArchive;

class Subjective extends Model
{
    protected $fillable = [
        'id', 'topic', 'answer', 'is_exist',
        'tag', 'created_at', 'updated_at'
    ];

    public function tag()
    {
        return $this->belongsTo('App\Models\Tag', 'tag');
    }

    /**
     * (出题用)通过tag_id获取题目
     * @param $tag_id
     * @return mixed
     */
    public static function GetRandomQuestionsByTagId($tag_id, $num)
    {
        return Tag::find($tag_id)->subjectives()
            ->where('is_exist', 1)
            ->inRandomOrder()
            ->take($num)
            ->select('id', 'topic')
            ->get();
    }

    /**
     * 题目信息数据处理
     * @param $question1
     * @param $question2
     * @param $tag_id
     * @return mixed
     */
    public static function OneQuestion($question1, $question2, $tag_id)
    {
        $question1['topic'] = $question2['topic'];
        if (isset($question2['answer'])){
            $question1['answer'] = $question2['answer'];
        }
        $question1['tag'] = $tag_id;
        $question1['updated_at'] = date('Y-m-d H:i:s');
        return $question1;
    }

    /**
     * 题目信息增删改
     * @param $question2
     * @param $tag_id
     * @return int
     */
    public static function OneQuestionProcess($question2, $tag_id)
    {
        if (isset($question2['id'])) {
            $id = $question2['id'];
            $question1 = Subjective::find($id);
            self::OneQuestion($question1, $question2, $tag_id);
            $question1->save();
        } else {
            $question1['created_at'] = date('Y-m-d H:i:s');
            $question1 = self::OneQuestion($question1, $question2, $tag_id);
            Subjective::insert($question1);
        }
    }

    /**
     * 通过tag_id获取题目
     * @param $tag_id
     * @return mixed
     */
    public static function GetQuestionsByTagId($tag_id)
    {
        return Subjective::where('is_exist', true)
            ->where('tag', $tag_id)
            ->get();
    }

    /**
     * 获取主观题答题记录
     * @param $twt_id
     * @param $paper_id
     * @return Model|Builder|object|null
     */
    public static function check($twt_id, $paper_id)
    {
        return DB::table('sub_records')
            ->where('twt_id', $twt_id)
            ->where('paper_id', $paper_id)
            ->first();
    }

    /**
     * 考试开始数据记录
     * @param $twt_id
     * @param $paper_id
     * @return mixed
     */
    public static function record($twt_id, $paper_id)
    {
        DB::table('sub_records')
            ->updateOrInsert(
                ['twt_id' => ''.$twt_id, 'paper_id' => ''.$paper_id],
                ['started_at' => date('Y-m-d H:i:s')]
            );
    }

    /**
     * 考试结束记录
     * @param $twt_id
     * @param $paper_id
     * @param $records
     */
    public static function Rrecord($twt_id, $paper_id, $records)
    {
        DB::table('sub_records')
            ->where('twt_id', $twt_id)
            ->where('paper_id', $paper_id)
            ->increment(
                'time',
                1,
                [
                    'record' => json_encode($records),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            );
    }

    public static function GetPaperName($paper_id){
        return Paper::find($paper_id)->name;
    }

    public static function getAnswer($paper_id){
        $paper_name = Paper::find($paper_id)->name;
        $answers = array();
        $twt_ids = array();
        $ques = array();
        $questions = Tag::with('subjectives')
            ->where('paper',$paper_id)
            ->where('is_subjective',1)
            ->select('id')
            ->get();
        foreach ($questions as $question){
            $sub = $question->subjectives;
            foreach ($sub as $s){
                $ques[''.$s->id] = $s->topic;
            }
        }
        $questions = $ques;
        $ans = DB::table('sub_records')
            ->where('paper_id',$paper_id)
            ->select('twt_id','paper_id','record')
            ->get();
        foreach ($ans as $an){
            $as = array();
            $twt_id = $an->twt_id;
            $answers[''.$twt_id] = $an;
            $record = json_decode($an->record);
            if (isset($record)){
                foreach ($record as $rec){
                    $a = $rec->answers;
                    foreach ($a as $id=>$b){
                        $as[''.$id] = $b;
                    }
                }
            }
            $an->answers = $as;
            unset($an->record);
            array_push($twt_ids,$twt_id);
        }
        $stu_infos = Student::whereIn('twt_id',$twt_ids)
            ->select('twt_id','stu_id','academic','real_name')
            ->get();
        foreach ($stu_infos as $stu_info){
            $twt_id = $stu_info->twt_id;
            $answers[''.$twt_id]->paper_name = $paper_name;
            $answers[''.$twt_id]->stu_id = $stu_info->stu_id;
            $answers[''.$twt_id]->academic = $stu_info->academic;
            $answers[''.$twt_id]->real_name = $stu_info->real_name;
            $answers[''.$twt_id]->questions = $questions;
        }
        return $answers;
    }

    public static function GenSingleDoc($record){
        $stu_id = $record->stu_id;
        $real_name = $record->real_name;
        $academic = $record->academic;
        $questions = $record->questions;
        $answers = $record->answers;
        $doc = new PhpWord();
        $section = $doc->addSection();
        $doc->addTitleStyle(1,['size'=>20,'bold'=>true]);
        $doc->addFontStyle('topic',['size'=>16,'bold'=>true]);
        $doc->addFontStyle('answer',['size'=>15]);
        $doc->addFontStyle('info',['size'=>14]);
        $section->addTitle($record->paper_name,1);
        $section->addTextBreak(2);
        $section->addText($stu_id.'  '.$real_name.'  '.$academic,'info');
        $section->addTextBreak(2);
        foreach ($questions as $id=>$question){
            $section->addText($question,'topic');
            $section->addTextBreak(1);
            if (isset($answers[''.$id])){
                $section->addText($answers[''.$id],'answer');
            }
            $section->addTextBreak(2);
        }
        $writer = IOFactory::createWriter($doc,'Word2007');
        $name = $stu_id.'_'.$real_name.'_'.$academic.'.docx';
        $writer->save($name);
        return $name;
    }

    public static function GenDocs($records,$zipFileName){
        $fileList = array();
        $zip = new ZipArchive();
        $zip->open('storage/'.$zipFileName,ZipArchive::CREATE);
        foreach ($records as $twt_id=>$record){
            $name = self::GenSingleDoc($record);
            $zip->addFile($name,basename($name));
            array_push($fileList,$name);
        }
        $zip->close();
        foreach ($fileList as $item){
            unlink($item);
        }
        return 'storage/'.$zipFileName;
    }

}

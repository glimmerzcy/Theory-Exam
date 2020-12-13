<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'id', 'topic', 'objA', 'objB', 'objC',
        'objD', 'objE', 'objF', 'answer', 'is_exist',
        'tag', 'created_at', 'updated_at'
    ];

    public function tag()
    {
        return $this->belongsTo('App\Models\Tag', 'tag');
    }

    /**
     * 答案转换算法（数字=>字母）
     * @param $num
     * @return string
     */
    public static function AnswerNumToStr($num)
    {
        $letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        $res = null;
        $e = 5;
        while ($num > 0) {
            if ($num < pow(2, $e)) {
                $e--;
            } else {
                $num -= pow(2, $e);
                $res .= $letters[$e];
                $e--;
            }
        }
        return strrev($res);
    }

    /**
     * 答案转换算法（字母=>数字）
     * @param $answer
     * @return int
     */
    public static function AnswerStrToNum($answer)
    {
        $ans = 0;
        for ($j = 0; $j < strlen($answer); $j++) {
            switch (substr($answer, $j, 1)) {
                case "A":
                    $ans += 1;
                    break;
                case "B":
                    $ans += 2;
                    break;
                case "C":
                    $ans += 4;
                    break;
                case "D":
                    $ans += 8;
                    break;
                case "E":
                    $ans += 16;
                    break;
                case "F":
                    $ans += 32;
                    break;
            }
        }
        return $ans;
    }

    /**
     * 通过tag_id获取题目
     * @param $tag_id
     * @return mixed
     */
    public static function GetQuestionsByTagId($tag_id)
    {
        $questions = Question::where('is_exist', true)
            ->where('tag', $tag_id)
            ->get();
        foreach ($questions as $question) {
            $question->answer = self::AnswerNumToStr($question->answer);
        }
        return $questions;
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
        $question1['objA'] = $question2['objA'];
        $question1['objB'] = $question2['objB'];
        $question1['objC'] = $question2['objC'];
        $question1['objD'] = $question2['objD'];
        $question1['objE'] = $question2['objE'];
        $question1['objF'] = $question2['objF'];
        $question1['answer'] = self::AnswerStrToNum($question2['answer']);
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
            $question1 = Question::find($id);
            $question1 = self::OneQuestion($question1, $question2, $tag_id);
            $question1->save();
        } else {
            $question1 = array();
            $question1['created_at'] = date('Y-m-d H:i:s');
            $question1 = self::OneQuestion($question1, $question2, $tag_id);
            Question::insert($question1);
        }
    }

    /**
     * (出题用)通过tag_id获取题目
     * @param $tag_id
     * @return mixed
     */
    public static function GetRandomQuestionsByTagId($tag_id, $num)
    {
        $questions = Tag::find($tag_id)->questions()
            ->where('is_exist', true)
            ->inRandomOrder()
            ->take($num)
            ->select('id', 'topic', 'objA', 'objB', 'objC', 'objD', 'objE', 'objF', 'answer')
            ->get();
        foreach ($questions as $question) {
            if (($question['answer']  == 1 or 2 or 4 or 8) || $question['answer'] == 1) {
                $question['type'] = 0;
            } else {
                $question['type'] = 1;
            }
            unset($question['answer']);
        }
        return $questions;
    }

    /**
     * 获取题号
     * @param $questions
     * @return array
     */
    public static function GetQuestionIds($questions)
    {
        $question_ids = array();
        foreach ($questions as $question) {
            array_push($question_ids, $question['id']);
        }
        return $question_ids;
    }
}

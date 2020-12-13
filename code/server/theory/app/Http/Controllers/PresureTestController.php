<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\Question;
use App\Models\Score;
use App\Models\Tag;
use Illuminate\Http\Request;

class PresureTestController extends Controller
{
    public function PaperGet(Request $request)
    {
        $college_code = 216;
        $head = Paper::find(49);
        $head['name'] = 'asdshijuan';
        unset($head['id'], $head['college_code'], $head['status'], $head['is_exist'], $head['created_at'], $head['updated_at']);
        $body = Tag::with('questions')->where('paper', 49)->get();
        foreach ($body as $part) {
            unset($part['id'], $part['is_exist'], $part['created_at'], $part['updated_at']);
            foreach ($part['questions'] as $question) {
                unset($question['id'], $question['is_exist'], $question['created_at'], $question['updated_at'], $question['tag']);
                $question['answer'] = Question::AnswerNumToStr($question['answer']);
            }
        }
        $id = Paper::PaperHeadProcess($head, $college_code);
        Paper::PaperBodyProcess($body, $id);
        return $id;
    }

    public function ans(Request $request)
    {
        $twt_id = 99999;
        $paper_id = 148;
        $record = Score::where('twt_id', 113840)->where('paper_id', 139)->first();
        $record = json_decode($record->record);
        $score = Score::Calculate($record);
        Score::ScoreRecord($twt_id, $paper_id, $record, $score, $request->userAgent());
        return 1;
    }

    public function draw(Request $request)
    {
        $twt_id = 99999;
        $paper_id = 49;
        return Paper::draw($paper_id, $twt_id, 0);
    }
}

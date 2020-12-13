<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Paper;
use App\Models\Result;
use App\Models\Score;
use App\Models\Student;
use App\Models\Subjective;
use App\Repositories\Fields\ScoreRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    protected $scoreRepository;

    public function __construct(
        ScoreRepository $scoreRepository
    ){
        $this->scoreRepository = $scoreRepository;
    }

    /**
     * 判题用
     * @param Request $request
     * @return JsonResponse
     */
    public function ScoreCheck(Request $request)
    {
        $twt_id = $request->session()->get('data')['id'];
        $paper_id = $request->paper_id;
        $record = $request->record;
        $userAgent = $request->userAgent();
        $score = $this->scoreRepository->Calculate($record);
        $data = $this->scoreRepository->ScoreRecord($twt_id, $paper_id, $score, $userAgent);
        Score::HistoryRecord($twt_id,$paper_id,$data['time'],$data['started_at'],$record,$score,$userAgent);
        if ($request->has('sub_record')) {
            Subjective::Rrecord($twt_id, $paper_id, $request->sub_record);
        }
        return Result::SuccessReturnData(1, $score);
    }
}

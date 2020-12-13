<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Paper;
use App\Models\Result;
use App\Repositories\Fields\PaperRepository;
use App\Repositories\Fields\ScoreRepository;
use App\Supports\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DrawQuestionsController extends Controller
{

    protected $paperRepository, $scoreRepository;

    public function __construct(
        PaperRepository $paperRepository,
        ScoreRepository $scoreRepository
    ){
        $this->paperRepository = $paperRepository;
        $this->scoreRepository = $scoreRepository;
    }

    /**
     * 抽题
     * @param Request $request
     * @return JsonResponse
     */
    public function DrawQuestions(Request $request)
    {
        $userAgent = $request->userAgent();
        if (!Student::UACheck($userAgent)) {
            return Result::ErrorReturn(1, 'Wrong Device !');
        } else {
            $twt_id = $request->session()->get('data')['id'];
            $paper_id = $request->paper_id;
            $paper = $this->paperRepository->getById($paper_id);
            $scoreWhere = array(
                ['paper_id',$paper_id],
                ['twt_id',$twt_id]
            );
            $score = $this->scoreRepository->getSingleRecord($scoreWhere,false);
            if ((isset($score['time']) && $score['time'] >= $paper['test_time']) || strtotime($paper["ended_at"]) < time()) {
                return Result::ErrorReturn(1, 'Run out of time');
            } else {
                return Result::SuccessReturnData(1, Paper::draw($paper_id, $twt_id, $paper['has_sub']));
            }
        }
    }
}

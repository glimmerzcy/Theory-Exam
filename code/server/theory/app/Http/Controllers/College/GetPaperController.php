<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\Paper;
use App\Models\Question;
use App\Models\Result;
use App\Models\Subjective;
use App\Models\Tag;
use App\Repositories\Fields\PaperRepository;
use App\Repositories\Fields\TagRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetPaperController extends Controller
{
    protected $paperRepository,$tagRepository;

    public function __construct(
        PaperRepository $paperRepository,
        TagRepository $tagRepository
    ){
        $this->paperRepository = $paperRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * 常规试卷基本信息
     * @param Request $request
     * @return JsonResponse
     */
    public function GetPapersHead(Request $request){
        $twt_id = $request->session()->get('data')['id'];
        return Result::SuccessReturnData(1,$this->paperRepository->GetTests($twt_id));
    }

    /**
     * 常规试卷详情
     * @param Request $request
     * @return JsonResponse
     */
    public function GetPaperDetail(Request $request){
        $paper_id = $request->paper_id;
        $head = $this->paperRepository->getById($paper_id);
        $body = $this->tagRepository->GetTagsByPaperId($paper_id);
        foreach ($body as $part){
            $tag_id=$part['id'];
            $is_sub = $part['is_subjective'];
            if ($is_sub == 1){
                $part['questions']=Subjective::GetQuestionsByTagId($tag_id);
            }else{
                $part['questions']=Question::GetQuestionsByTagId($tag_id);
            }
        }
        $paper=array();
        $paper['head']=$head;
        $paper['body']=$body;
        return Result::SuccessReturnData(1,$paper);
    }

    /**
     * 补考试卷详情
     * @param Request $request
     * @return JsonResponse
     */
    public function GetMakeupPaperDetail(Request $request){
        $paper_id = $request->paper_id;
        $head = $this->paperRepository->getById($paper_id);
        return Result::SuccessReturnData(1,$head);
    }
}

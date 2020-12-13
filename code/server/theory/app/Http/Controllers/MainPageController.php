<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\Paper;
use App\Models\Result;
use App\Models\Score;
use App\Repositories\Fields\NoticeRepository;
use App\Repositories\Fields\PaperRepository;
use App\Repositories\Fields\ScoreRepository;
use Illuminate\Http\Request;

class MainPageController extends Controller
{
    protected $noticeRepository, $paperRepository, $scoreRepository;

    public function __construct(
        NoticeRepository $noticeRepository,
        PaperRepository $paperRepository,
        ScoreRepository $scoreRepository
    ){
        $this->noticeRepository = $noticeRepository;
        $this->paperRepository = $paperRepository;
        $this->scoreRepository = $scoreRepository;
    }

    public function MainPage(Request $request)
    {
        //排名系统需求不明确，paper_id管理员自行定义
        $paper_id = 61;
        $data = array(
            'notice'=>$this->noticeRepository->getNotices(),
            'tests'=>$this->paperRepository->Released(),
            'ranks'=>$this->scoreRepository->allRank($paper_id)
        );
        return Result::SuccessReturnData(1, $data);
    }

    public function session(Request $request)
    {
        return $request->session()->all();
    }

    public function host(Request $request){
        return $request->getHost();
    }

    public function welcome(){
        return redirect('/home');
    }

}

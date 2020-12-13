<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\Paper;
use App\Models\Result;
use App\Repositories\Fields\PaperRepository;
use App\Repositories\Fields\TagRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PaperEditController extends Controller
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
     * 正常试卷头编辑
     * @param Request $request
     * @return JsonResponse
     */
    public function HeadEdit(Request $request){
        $head = $request->head;
        $twt_id = $request->session()->get('data')['id'];
        $id = $this->paperRepository->PaperHeadProcess($head,$twt_id);
        return Result::SuccessReturnData(1,$id);
    }

    /**
     * 补考试卷头编辑
     * @param Request $request
     * @return JsonResponse
     */
    public function MakeUpEdit(Request $request){
        $paper = $request->paper;
        $twt_id = $request->session()->get('data')['id'];
        $head = $paper['head'];
        $id = $this->paperRepository->MakeUpPaperHeadProcess($head,$twt_id);
        return Result::SuccessReturnData(1,'makeup edit succeed');
    }

    /**
     * 模版下载
     * @return BinaryFileResponse
     */
    public function ModelExcelDownload(){
        $path = 'model.xls';
        return response()->download(storage_path($path));
    }

    /**
     * 题组头编辑
     * @param Request $request
     * @return null
     */
    public function TagEdit(Request $request){
        return null;
    }

    /**
     * 题目编辑
     * @param Request $request
     * @return null
     */
    public function BodyProcess(Request $request){
        return null;
    }

    /**
     * 删除试卷
     * @param Request $request
     * @return JsonResponse
     */
    public function DeletePaper(Request $request){
        $paper_id=$request->paper_id;
        $this->paperRepository->DeletePaper($paper_id);
        return Result::SuccessReturnMessage(1,'delete success');
    }

    /*########################*/
    /* 以下老接口 */
    /*########################*/

    /**
     * 正常试卷题目编辑
     * @param Request $request
     * @return JsonResponse
     */
    public function BodyEdit(Request $request){
        $id = $request->paper_id;
        $body = $request->body;
        Paper::PaperBodyProcess($body,$id);
        return Result::SuccessReturnData(1,$id);
    }

    /**
     * 正常试卷编辑
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(Request $request){
        $paper = $request->paper;
        $twt_id = $request->session()->get('data')['id'];
        $head = $paper['head'];
        $body = $paper['body'];

        $id = Paper::PaperHeadProcess($head,$twt_id);
//            $id = Paper::PaperHeadProcessOld($head,$college_code);

        Paper::PaperBodyProcess($body,$id);
        return Result::SuccessReturnData(1,$id);
    }

}

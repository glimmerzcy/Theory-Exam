<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\Paper;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReleaseController extends Controller
{
    /**
     * 试卷发布
     * @param Request $request
     * @return JsonResponse
     */
    public function release(Request $request){
            $paper_id = $request->paper_id;
            $paper = Paper::find($paper_id);
            $aim = $paper['aim'];
            if ($aim == 0){
                $status = Paper::UniPaperRelease($paper_id);
            }elseif ($aim == 1){
                $college_codes = $request->college_codes;
                $status = Paper::ColPaperRelease($paper_id,$college_codes);
            }else{
                $twt_ids=Student::StuIdsTransToTwTIds($request->stu_ids);
                $status = Paper::SmallPaperRelease($paper_id,$twt_ids);
            }
            return Result::SuccessReturnData(1,["exeptions"=>$status]);
    }
}

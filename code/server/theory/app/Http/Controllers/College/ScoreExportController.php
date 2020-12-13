<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Request;
use App\Models\Score;

class ScoreExportController extends Controller
{
    public function ScoreExport(Request $request){
        $paper_id = $request->paper_id;
        $data = Score::ScoresExport($paper_id);
        return Result::SuccessReturnData(1,$data);
    }
}

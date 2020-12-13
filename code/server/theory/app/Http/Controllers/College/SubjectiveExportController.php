<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\Subjective;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Shared\ZipArchive;

class SubjectiveExportController extends Controller
{
    public function SubDownload(Request $request){
        $paper_id = $request->get('paper_id');
        $answers = Subjective::getAnswer($paper_id);
        $zipFileName = Subjective::GetPaperName($paper_id).'主观题答题记录.zip';
        $downloadPath = Subjective::GenDocs($answers,$zipFileName);
        return response()->download($downloadPath);
    }
}

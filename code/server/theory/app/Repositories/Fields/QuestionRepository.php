<?php


namespace App\Repositories\Fields;

use App\Models\Question;
use App\Repositories\BaseRepository;

class QuestionRepository extends BaseRepository
{

    protected $model;

    public function __construct(Question $question){
        $this->model = $question;
    }


}

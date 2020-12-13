<?php


namespace App\Repositories\Fields;


use App\Models\Paper;
use App\Models\Tag;
use App\Repositories\BaseRepository;

class TagRepository extends BaseRepository
{
    protected $model,$paper;

    public function __construct(Tag $tag, Paper $paper){
        $this->model = $tag;
        $this->paper = $paper;
    }

    public function GetTagsByPaperId($paper_id)
    {
        return $this->paper->find($paper_id)->tags()
            ->where('is_exist', true)
            ->get();
    }
}

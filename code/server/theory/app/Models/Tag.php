<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'id', 'name', 'credit_per_question', 'draw_quantity', 'is_exist',
        'created_at', 'updated_at'
    ];

    public function questions()
    {
        return $this->hasMany('App\Models\Question', 'tag');
    }

    public function subjectives()
    {
        return $this->hasMany('App\Models\Subjective', 'tag');
    }

    public function paper()
    {
        return $this->belongsTo('App\Models\Paper', 'paper');
    }

    /**
     * 指定试卷题组信息获取
     * @param $paper_id
     * @return Builder[]|Collection
     */
    public static function GetTagsByPaperId($paper_id)
    {
        return Paper::find($paper_id)->tags()
            ->where('is_exist', true)
            ->get();
    }

    /**
     * 指定试卷主观题题组信息获取
     * @param $paper_id
     * @return void
     */
    public static function GetSubTagsIdsByPaperId($paper_id)
    {
        $tags = Tag::with('paper')
            ->where('paper', $paper_id)
            ->where('is_subjective', true)
            ->where('is_exist', true)
            ->get();
        $tag_ids = array();
        foreach ($tags as $tag) {
            array_push($tag_ids, $tag->id);
        }
    }

    /**
     * 题组信息数据处理
     * @param $tag1
     * @param $tag2
     * @param $paper_id
     * @return mixed
     */
    public static function OneTag($tag1, $tag2, $paper_id)
    {
        $tag1['name'] = $tag2['name'];
        $tag1['credit_per_question'] = $tag2['credit_per_question'];
        $tag1['draw_quantity'] = $tag2['draw_quantity'];
        $tag1['paper'] = $paper_id;
        $tag1['updated_at'] = date('Y-m-d H:i:s');
        $tag1['is_subjective'] = $tag2['is_subjective'];
        return $tag1;
    }

    /**
     * 题组信息增删改
     * @param $tag2
     * @param $paper_id
     * @return mixed
     */
    public static function OneTagProcess($tag2, $paper_id)
    {
        if (isset($tag2['id'])) {
            $id = $tag2['id'];
            $tag1 = Tag::find($id);
            self::OneTag($tag1, $tag2, $paper_id);
            $tag1->save();
            return $id;
        } else {
            $tag1['created_at'] = date('Y-m-d H:i:s');
            $tag1 = self::OneTag($tag1, $tag2, $paper_id);
            $id = Tag::insertGetId($tag1);
            return $id;
        }
    }
}

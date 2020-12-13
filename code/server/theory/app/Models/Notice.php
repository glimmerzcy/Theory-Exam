<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Notice extends Model
{
    protected $fillable = [
        'title','content','published_at','created_at','college_code'
    ];
}

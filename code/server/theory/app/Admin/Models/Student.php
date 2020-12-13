<?php

namespace App\Admin\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
	use HasDateTimeFormatter;
    public $timestamps = false;

    public function permission(){
        return $this->belongsTo(Permission::class,'twt_id','twt_id');
    }
}

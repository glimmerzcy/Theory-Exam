<?php

namespace App\Admin\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	use HasDateTimeFormatter;
    public $timestamps = false;

    public function student(){
        return $this->hasOne(Student::class,'twt_id','twt_id');
    }

}

<?php

namespace App\Admin\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
	use HasDateTimeFormatter;    
    public $timestamps = false;

}

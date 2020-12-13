<?php


namespace App\Repositories\Fields;

use App\Models\Permission;
use App\Repositories\BaseRepository;

class PermissionRepository extends BaseRepository
{
    protected $model;

    public function __construct(Permission $permission){
        $this->model = $permission;
    }

    public function GetPermissionCode($twt_id){
        $where = array(['twt_id',$twt_id]);
        $per = $this->getSingleRecord($where,false);
        if (isset($per->activated)){
            return $per->activated;
        }else{
            return 0;
        }
    }
}

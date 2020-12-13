<?php
namespace App\Admin\Repositories;

use Dcat\Admin\Repositories\EloquentRepository;
use App\Admin\Models\Permission as PermissionModel;

class Permission extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = PermissionModel::class;
}

<?php

namespace App\Admin\Repositories;

use App\Admin\Models\Score as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Score extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

<?php

namespace App\Admin\Repositories;

use App\Admin\Models\Subjective as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Subjective extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

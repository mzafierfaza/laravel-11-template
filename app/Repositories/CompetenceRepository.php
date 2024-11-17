<?php

namespace App\Repositories;

use App\Models\Competence;

class CompetenceRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Competence();
    }
}

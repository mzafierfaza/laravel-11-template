<?php

namespace App\Repositories;

use App\Models\Aabbccdd;

class AabbccddRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Aabbccdd();
    }
}

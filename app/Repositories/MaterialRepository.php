<?php

namespace App\Repositories;

use App\Models\Material;

class MaterialRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Material();
    }
}

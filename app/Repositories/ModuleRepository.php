<?php

namespace App\Repositories;

use App\Models\Module;

class ModuleRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Module();
    }
}

<?php

namespace App\Repositories;

use App\Models\CoreRole;

class CoreRoleRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new CoreRole();
    }
}

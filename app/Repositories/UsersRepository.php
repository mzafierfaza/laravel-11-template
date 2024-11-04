<?php

namespace App\Repositories;

use App\Models\Users;

class UsersRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Users();
    }
}

<?php

namespace App\Repositories;

use App\Models\RegisterLog;

class RegisterLogRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new RegisterLog();
    }
}

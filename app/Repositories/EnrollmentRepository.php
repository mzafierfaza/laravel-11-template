<?php

namespace App\Repositories;

use App\Models\Enrollment;

class EnrollmentRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Enrollment();
    }
}

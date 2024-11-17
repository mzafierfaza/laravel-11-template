<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Course();
    }
}

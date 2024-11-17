<?php

namespace App\Repositories;

use App\Models\CompetenceCourse;

class CompetenceCourseRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new CompetenceCourse();
    }
}

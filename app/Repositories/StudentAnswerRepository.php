<?php

namespace App\Repositories;

use App\Models\StudentAnswer;

class StudentAnswerRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new StudentAnswer();
    }
}

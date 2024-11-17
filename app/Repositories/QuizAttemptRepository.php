<?php

namespace App\Repositories;

use App\Models\QuizAttempt;

class QuizAttemptRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new QuizAttempt();
    }
}

<?php

namespace App\Repositories;

use App\Models\Quiz;

class QuizRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Quiz();
    }
}

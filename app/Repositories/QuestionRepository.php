<?php

namespace App\Repositories;

use App\Models\Question;

class QuestionRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Question();
    }
}

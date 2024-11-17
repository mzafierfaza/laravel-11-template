<?php

namespace App\Repositories;

use App\Models\QuestionOption;

class QuestionOptionRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new QuestionOption();
    }
}

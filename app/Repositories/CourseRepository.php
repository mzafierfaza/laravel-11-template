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

    public function getAll()
    {
        return $this->model->where('approved_status', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->mapWithKeys(function ($course) {
                $topic = $course->topic;
                $title = $course->title;
                if ($topic) {
                    $title = $title . ' (' . $topic . ')';
                }
                return [
                    $course->id => $title
                ];
            })
            ->toArray();
    }
}

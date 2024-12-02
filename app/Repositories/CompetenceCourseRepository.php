<?php

namespace App\Repositories;

use App\Models\CompetenceCourse;

class CompetenceCourseRepository extends Repository
{
    public function __construct()
    {
        $this->model = new CompetenceCourse();
    }
    public function getAllExcept($competence_id)
    {
        $alreadys = $this->model->where("competence_id", $competence_id)
            ->select("course_id")
            ->get();

        $alreadys = $alreadys->map(function ($item) {
            return $item->toArray();
        });

        $notIn = [];
        foreach ($alreadys as $already) {
            $notIn[] = $already['course_id'];
        }

        return $this->model
            ->join('courses', 'competence_courses.course_id', '=', 'courses.id')
            ->where('approved_status', 1)
            ->whereNotIn('courses.id', $notIn)
            ->orderBy('courses.created_at', 'desc')
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

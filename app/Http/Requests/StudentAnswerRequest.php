<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('put')) {
            return [
				'quiz_attempt_id' => ["required"],
				'question_id' => ["required"],
				'selected_option_id' => [],
				'essay_answer' => [],
				'score' => [],
				'teacher_comment' => [],
				'deleted_at' => [],

            ];
        }
        return [
			'quiz_attempt_id' => ["required"],
			'question_id' => ["required"],
			'selected_option_id' => [],
			'essay_answer' => [],
			'score' => [],
			'teacher_comment' => [],
			'deleted_at' => [],

        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizAttemptRequest extends FormRequest
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
				'enrollment_id' => ["required"],
				'quiz_id' => ["required"],
				'start_time' => [],
				'submit_time' => [],
				'score' => [],
				'is_passed' => [],
				'deleted_at' => [],

            ];
        }
        return [
			'enrollment_id' => ["required"],
			'quiz_id' => ["required"],
			'start_time' => [],
			'submit_time' => [],
			'score' => [],
			'is_passed' => [],
			'deleted_at' => [],

        ];
    }
}

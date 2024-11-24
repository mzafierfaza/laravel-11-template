<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizRequest extends FormRequest
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
				'module_id' => ["required"],
				'title' => [],
				'description' => [],
				'duration_minutes' => [],
				'passing_score' => [],
				'start_time' => [],
				'end_time' => [],
				'is_randomize' => [],
				'deleted_at' => [],

            ];
        }
        return [
			'module_id' => ["required"],
			'title' => [],
			'description' => [],
			'duration_minutes' => [],
			'passing_score' => [],
			'start_time' => [],
			'end_time' => [],
			'is_randomize' => [],
			'deleted_at' => [],

        ];
    }
}

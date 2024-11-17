<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionOptionRequest extends FormRequest
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
				'question_id' => ["required"],
				'option_text' => [],
				'is_correct' => [],
				'deleted_at' => [],

            ];
        }
        return [
			'question_id' => ["required"],
			'option_text' => [],
			'is_correct' => [],
			'deleted_at' => [],

        ];
    }
}

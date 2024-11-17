<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
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
				'user_id' => ["required"],
				'competence_id' => ["required"],
				'enrolled_date' => [],
				'status' => ["required"],
				'deleted_at' => [],

            ];
        }
        return [
			'user_id' => ["required"],
			'competence_id' => ["required"],
			'enrolled_date' => [],
			'status' => ["required"],
			'deleted_at' => [],

        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialRequest extends FormRequest
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
				'content' => [],
				'file_path' => [],
				'duration_minutes' => [],
				'type' => [],
				'order' => [],
				'is_progress' => [],
				'deleted_at' => [],

            ];
        }
        return [
			'module_id' => ["required"],
			'title' => [],
			'content' => [],
			'file_path' => [],
			'duration_minutes' => [],
			'type' => [],
			'order' => [],
			'is_progress' => [],
			'deleted_at' => [],

        ];
    }
}

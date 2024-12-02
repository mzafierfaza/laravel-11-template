<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoreRoleRequest extends FormRequest
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
				'name' => [],
				'group_id' => ["required"],

            ];
        }
        return [
			'name' => [],
			'group_id' => ["required"],

        ];
    }
}
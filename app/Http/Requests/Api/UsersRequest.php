<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UsersRequest extends FormRequest
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
				'firstname' => ["required"],
				'lastname' => [],
				'email' => ["required","email"],
				'gender' => [],
				'ktp' => [],
				'npwp' => [],
				'picture' => [],
				'date_of_birth' => ["date"],
				'region' => [],
				'phone' => ["required"],

            ];
        }
        return [
			'firstname' => ["required"],
			'lastname' => [],
			'email' => ["required","email"],
			'gender' => [],
			'ktp' => [],
			'npwp' => [],
			'picture' => [],
			'date_of_birth' => ["date"],
			'region' => [],
			'phone' => ["required"],

        ];
    }
}
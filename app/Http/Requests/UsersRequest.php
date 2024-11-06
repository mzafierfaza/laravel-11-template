<?php

namespace App\Http\Requests;

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
                'first_name' => ["required"],
                'last_name' => [],
                'email' => ["required", "email"],
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
            'first_name' => ["required"],
            'last_name' => [],
            'email' => ["required", "email"],
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

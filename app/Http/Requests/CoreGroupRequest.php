<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoreGroupRequest extends FormRequest
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
                'jenis_badan_usaha' => [],
                'bidang_usaha' => [],
                'owner_name' => [],
                'owner_ktp' => [],
                'owner_npwp' => [],
                'address' => [],
                'pic_name' => [],
                'pic_phone' => [],
                'pic_email' => [],

            ];
        }
        return [
            'name' => [],
            'jenis_badan_usaha' => [],
            'bidang_usaha' => [],
            'owner_name' => [],
            'owner_ktp' => [],
            'owner_npwp' => [],
            'address' => [],
            'pic_name' => [],
            'pic_phone' => [],
            'pic_email' => [],

        ];
    }
}

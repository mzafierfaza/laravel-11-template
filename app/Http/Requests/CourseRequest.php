<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
				'title' => [],
				'description' => [],
				'procedurs' => [],
				'topic' => [],
				'format' => ["required"],
				'is_random_material' => [],
				'is_premium' => [],
				'price' => [],
				'created_by' => ["required"],
				'is_active' => [],
				'start_date' => [],
				'end_date' => [],
				'start_time' => [],
				'end_time' => [],
				'address' => [],
				'is_repeat_enrollment' => [],
				'max_repeat_enrollment' => [],
				'max_enrollment' => [],
				'is_class_test' => [],
				'is_class_finish' => [],
				'status' => [],
				'approved_status' => [],
				'approved_at' => [],
				'approved_by' => ["required"],
				'teacher_id' => ["required"],
				'teacher_about' => [],
				'image' => [],
				'certificate' => [],
				'certificate_can_download' => [],

            ];
        }
        return [
			'title' => [],
			'description' => [],
			'procedurs' => [],
			'topic' => [],
			'format' => ["required"],
			'is_random_material' => [],
			'is_premium' => [],
			'price' => [],
			'created_by' => ["required"],
			'is_active' => [],
			'start_date' => [],
			'end_date' => [],
			'start_time' => [],
			'end_time' => [],
			'address' => [],
			'is_repeat_enrollment' => [],
			'max_repeat_enrollment' => [],
			'max_enrollment' => [],
			'is_class_test' => [],
			'is_class_finish' => [],
			'status' => [],
			'approved_status' => [],
			'approved_at' => [],
			'approved_by' => ["required"],
			'teacher_id' => ["required"],
			'teacher_about' => [],
			'image' => [],
			'certificate' => [],
			'certificate_can_download' => [],

        ];
    }
}

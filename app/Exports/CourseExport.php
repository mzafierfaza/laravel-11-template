<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class CourseExport implements FromView
{
    use Exportable;

    /**
     * data
     *
     * @var Collection
     */
    private Collection $data;

    /**
     * constructor method
     *
     * @param Collection $data
     * @return void
     */
    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    /**
     * export from view
     *
     * @return View
     */
    public function view(): View
    {
        if ($this->data->count() === 0) {
            $columns = [
				'title',
				'description',
				'procedurs',
				'topic',
				'format',
				'is_random_material',
				'is_premium',
				'price',
				'created_by',
				'is_active',
				'start_date',
				'end_date',
				'start_time',
				'end_time',
				'address',
				'is_repeat_enrollment',
				'max_repeat_enrollment',
				'max_enrollment',
				'is_class_test',
				'is_class_finish',
				'status',
				'approved_status',
				'approved_at',
				'approved_by',
				'teacher_id',
				'teacher_about',
				'image',
				'certificate',
				'certificate_can_download',
            ];
            $data = [];

            foreach (range(1, 10) as $i) {
                array_push($data, (object) array_combine($columns, $columns));
            }

            $this->data = collect($data);
        }
        return view('stisla.courses.export-excel-example', [
            'data'     => $this->data,
            'isExport' => true
        ]);
    }
}

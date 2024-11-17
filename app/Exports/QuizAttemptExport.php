<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class QuizAttemptExport implements FromView
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
				'enrollment_id',
				'quiz_id',
				'start_time',
				'submit_time',
				'score',
				'is_passed',
				'deleted_at',
            ];
            $data = [];

            foreach (range(1, 10) as $i) {
                array_push($data, (object) array_combine($columns, $columns));
            }

            $this->data = collect($data);
        }
        return view('stisla.quiz-attempts.export-excel-example', [
            'data'     => $this->data,
            'isExport' => true
        ]);
    }
}

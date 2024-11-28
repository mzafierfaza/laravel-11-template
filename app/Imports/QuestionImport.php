<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToCollection, WithHeadingRow
{
    protected $quiz_id;
    protected $is_essay;

    public function __construct($quiz_id, $is_essay)
    {
        $this->quiz_id = $quiz_id;
        $this->is_essay = $is_essay;
    }

    public function collection(Collection $rows)
    {
        // dd($this->quiz_id);
        $dateTime = date('Y-m-d H:i:s');
        $order = 1;
        foreach ($rows->chunk(30) as $chunkData) {
            $insertData = $chunkData->transform(function ($item) use ($dateTime, $order) {
                // dd($item);
                $item->put('created_at', $dateTime);
                $item->put('updated_at', $dateTime);
                $data = [
                    'quiz_id' => $this->quiz_id,
                    'is_essay' => $this->is_essay,
                    'question_text' => $item['question'],
                    'paket' => $item['paket'],
                    'correct_answer' => $item['answer'],
                    'choice_a' => $item['choice_a'],
                    'choice_b' => $item['choice_b'],
                    'choice_c' => $item['choice_c'],
                    'choice_d' => $item['choice_d'],
                    'choice_e' => $item['choice_e'],
                ];
                $order++;
                return $data;
            })->toArray();
            \App\Models\Question::insert($insertData);
        }
    }
}

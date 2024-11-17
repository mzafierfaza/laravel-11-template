<?php

namespace Database\Seeders;

use App\Models\StudentAnswer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudentAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data  = [];
        $faker = \Faker\Factory::create('id_ID');
        $now   = date('Y-m-d H:i:s');

        StudentAnswer::truncate();

        foreach (range(1, 20) as $i) {
            array_push($data, [
                'quiz_attempt_id' => Str::random(10),
				'question_id' => Str::random(10),
				'selected_option_id' => Str::random(10),
				'essay_answer' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'score' => Str::random(10),
				'teacher_comment' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'deleted_at' => Str::random(10),
				'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $chunkeds = collect($data)->chunk(20);
        foreach ($chunkeds as $chunkData) {
            StudentAnswer::insert($chunkData->toArray());
        }
    }
}

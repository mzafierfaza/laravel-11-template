<?php

namespace Database\Seeders;

use App\Models\QuizAttempt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuizAttemptSeeder extends Seeder
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

        QuizAttempt::truncate();

        foreach (range(1, 20) as $i) {
            array_push($data, [
                'enrollment_id' => Str::random(10),
				'quiz_id' => Str::random(10),
				'start_time' => Str::random(10),
				'submit_time' => Str::random(10),
				'score' => Str::random(10),
				'is_passed' => Str::random(10),
				'deleted_at' => Str::random(10),
				'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $chunkeds = collect($data)->chunk(20);
        foreach ($chunkeds as $chunkData) {
            QuizAttempt::insert($chunkData->toArray());
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuestionSeeder extends Seeder
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

        Question::truncate();

        foreach (range(1, 20) as $i) {
            array_push($data, [
                'quiz_id' => Str::random(10),
				'question_text' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'type' => Str::random(10),
				'points' => Str::random(10),
				'correct_essay_answer' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'deleted_at' => Str::random(10),
				'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $chunkeds = collect($data)->chunk(20);
        foreach ($chunkeds as $chunkData) {
            Question::insert($chunkData->toArray());
        }
    }
}

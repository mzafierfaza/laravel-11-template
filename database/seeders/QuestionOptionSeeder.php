<?php

namespace Database\Seeders;

use App\Models\QuestionOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuestionOptionSeeder extends Seeder
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

        QuestionOption::truncate();

        foreach (range(1, 20) as $i) {
            array_push($data, [
                'question_id' => Str::random(10),
				'option_text' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'is_correct' => Str::random(10),
				'deleted_at' => Str::random(10),
				'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $chunkeds = collect($data)->chunk(20);
        foreach ($chunkeds as $chunkData) {
            QuestionOption::insert($chunkData->toArray());
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Quiz;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuizSeeder extends Seeder
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

        Quiz::truncate();

        foreach (range(1, 20) as $i) {
            array_push($data, [
                'module_id' => Str::random(10),
				'title' => Str::random(10),
				'description' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'duration_minutes' => Str::random(10),
				'passing_score' => Str::random(10),
				'start_time' => Str::random(10),
				'end_time' => Str::random(10),
				'is_randomize' => Str::random(10),
				'deleted_at' => Str::random(10),
				'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $chunkeds = collect($data)->chunk(20);
        foreach ($chunkeds as $chunkData) {
            Quiz::insert($chunkData->toArray());
        }
    }
}

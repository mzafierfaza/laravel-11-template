<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
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

        Course::truncate();

        foreach (range(1, 20) as $i) {
            array_push($data, [
                'title' => Str::random(10),
				'description' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'procedurs' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'topic' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'format' => Str::random(10),
				'is_random_material' => Str::random(10),
				'is_premium' => Str::random(10),
				'price' => Str::random(10),
				'created_by' => Str::random(10),
				'is_active' => Str::random(10),
				'start_date' => Str::random(10),
				'end_date' => Str::random(10),
				'start_time' => Str::random(10),
				'end_time' => Str::random(10),
				'address' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'is_repeat_enrollment' => Str::random(10),
				'max_repeat_enrollment' => Str::random(10),
				'max_enrollment' => Str::random(10),
				'is_class_test' => Str::random(10),
				'is_class_finish' => Str::random(10),
				'status' => Str::random(10),
				'approved_status' => Str::random(10),
				'approved_at' => Str::random(10),
				'approved_by' => Str::random(10),
				'teacher_id' => Str::random(10),
				'teacher_about' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'image' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'certificate' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'certificate_can_download' => Str::random(10),
				'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $chunkeds = collect($data)->chunk(20);
        foreach ($chunkeds as $chunkData) {
            Course::insert($chunkData->toArray());
        }
    }
}

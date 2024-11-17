<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EnrollmentSeeder extends Seeder
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

        Enrollment::truncate();

        foreach (range(1, 20) as $i) {
            array_push($data, [
                'user_id' => Str::random(10),
				'competence_id' => Str::random(10),
				'enrolled_date' => Str::random(10),
				'status' => Str::random(10),
				'deleted_at' => Str::random(10),
				'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $chunkeds = collect($data)->chunk(20);
        foreach ($chunkeds as $chunkData) {
            Enrollment::insert($chunkData->toArray());
        }
    }
}

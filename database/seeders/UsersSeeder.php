<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
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

        Users::truncate();

        foreach (range(1, 20) as $i) {
            array_push($data, [
                'firstname' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'lastname' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'email' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'gender' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'ktp' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'npwp' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'picture' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'date_of_birth' => Str::random(10),
				'region' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'phone' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $chunkeds = collect($data)->chunk(20);
        foreach ($chunkeds as $chunkData) {
            Users::insert($chunkData->toArray());
        }
    }
}

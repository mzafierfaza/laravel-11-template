<?php

namespace Database\Seeders;

use App\Models\Competence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompetenceSeeder extends Seeder
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

        Competence::truncate();

        foreach (range(1, 20) as $i) {
            array_push($data, [
                'title' => Str::random(10),
				'level' => Str::random(10),
				'certificate' => Str::random(10),
				'certificate_can_download' => Str::random(10),
				'image' => Str::random(10),
				'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $chunkeds = collect($data)->chunk(20);
        foreach ($chunkeds as $chunkData) {
            Competence::insert($chunkData->toArray());
        }
    }
}

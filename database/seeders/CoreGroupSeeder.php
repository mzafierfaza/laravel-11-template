<?php

namespace Database\Seeders;

use App\Models\CoreGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CoreGroupSeeder extends Seeder
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

        CoreGroup::truncate();

        foreach (range(1, 20) as $i) {
            array_push($data, [
                'name' => Str::random(10),
				'jenis_badan_usaha' => Str::random(10),
				'badan_usaha' => Str::random(10),
				'owner_name' => Str::random(10),
				'owner_ktp' => Str::random(10),
				'owner_npwp' => Str::random(10),
				'address' => Str::random(10),
				'pic_name' => Str::random(10),
				'pic_phone' => Str::random(10),
				'pic_email' => Str::random(10),
				'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $chunkeds = collect($data)->chunk(20);
        foreach ($chunkeds as $chunkData) {
            CoreGroup::insert($chunkData->toArray());
        }
    }
}

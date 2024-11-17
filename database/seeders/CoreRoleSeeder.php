<?php

namespace Database\Seeders;

use App\Models\CoreRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CoreRoleSeeder extends Seeder
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

        CoreRole::truncate();

        foreach (range(1, 20) as $i) {
            array_push($data, [
                'name' => Str::random(10),
				'group_id' => Str::random(10),
				'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $chunkeds = collect($data)->chunk(20);
        foreach ($chunkeds as $chunkData) {
            CoreRole::insert($chunkData->toArray());
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProdukSeeder extends Seeder
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

        Produk::truncate();

        foreach (range(1, 20) as $i) {
            array_push($data, [
                'name' => $faker->numberBetween(0,1000), // ganti method fakernya sesuai kebutuhan
				'umur' => Str::random(10),
				'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $chunkeds = collect($data)->chunk(20);
        foreach ($chunkeds as $chunkData) {
            Produk::insert($chunkData->toArray());
        }
    }
}

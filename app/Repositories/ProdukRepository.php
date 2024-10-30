<?php

namespace App\Repositories;

use App\Models\Produk;

class ProdukRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Produk();
    }
}

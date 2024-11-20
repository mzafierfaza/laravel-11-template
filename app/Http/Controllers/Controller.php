<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function getDropdownOptions($filejson)
    {
        $filePath = public_path('assets/dropdown/' . $filejson);
        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true); // true means array
        return collect($data)->pluck('label', 'value')->toArray();
    }
}

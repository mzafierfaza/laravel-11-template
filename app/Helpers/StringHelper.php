<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class StringHelper
{

    /**
     * get acronym from string
     *
     * @param string $words
     * @return string
     */

    public static function generateRandomString(int $length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function acronym(string $words, int $max = -1)
    {
        $words = explode(" ", $words);
        $acronym = "";
        if ($max === -1)
            foreach ($words as $w) {
                $acronym .= $w[0];
            }
        else {
            $i = 1;
            foreach ($words as $w) {
                $acronym .= $w[0];
                if ($i === $max) break;
                $i++;
            }
        }
        return $acronym;
    }

    /**
     * isUrl
     *
     * @param string $url
     * @return boolean
     */
    public static function isUrl(string $url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            return false;
        }
        return true;
    }
}

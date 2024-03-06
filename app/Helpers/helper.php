<?php

use Carbon\Carbon;

if (!function_exists('moneyFormat')) {
    function moneyFormat($value)
    {
        return number_format($value, 2, ',', '.') . ' ₺';
    }
}

if (!function_exists('generateUniqueCode')) {
    function generateUniqueCode($userId)
    {
        return $userId . time();
    }
}

if (!function_exists('calculateAge')){
    function calculateAge($birthdate) {

        // Carbon kütüphanesi ile doğum tarihini bir Carbon nesnesine dönüştürelim
        $birthdate = Carbon::createFromFormat('Y-m-d', $birthdate);

        // Şu anki tarihi alalım
        $now = Carbon::now();

        // Yaş hesabı yapalım
        $age = $now->diffInYears($birthdate);

        return $age;
    }

}

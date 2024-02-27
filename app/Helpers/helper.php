<?php

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

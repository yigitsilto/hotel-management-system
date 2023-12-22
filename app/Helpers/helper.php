<?php

if (!function_exists('moneyFormat')) {
    function moneyFormat($value)
    {
        return number_format($value, 2, ',', '.') . ' ₺';
    }
}
<?php

use NumberFormatter;
use Illuminate\Support\Number;

if (!function_exists('convertNumberToWords')) {
    function convertNumberToWords($amount)
    {
        $formatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        return ucfirst($formatter->format($amount));
    }
}

if (!function_exists('indianmoney')){
    function indianmoney($amount)
    {
        return Number::format($amount, locale: 'en_IN',precision: 2);
    }
}

<?php

/**
 * Ubah value menjadi convertion
 */
if (!function_exists('convertion_value')) {
    function convertion_value($value)
    {
        $convertion = 0;
        if ($value >= 60) {
            $convertion = 1;
        } else if ($value >= 50 && $value <= 59) {
            $convertion = 0.8;
        } else if ($value >= 30 && $value <= 49) {
            $convertion = 0.6;
        } else if ($value >= 10 && $value <= 29) {
            $convertion = 0.4;
        } else if ($value >= 0 && $value <= 9) {
            $convertion = 0.2;
        }

        return (float) $convertion;
    }
}

<?php

use Illuminate\Support\Facades\Log;
// Checks if word requires 'an' or 'a'
if (!function_exists('a0rAn')) {
    function a0rAn($word)
    {
        $firstLetter = strtolower(substr($word, 0, 1));
        $vowels = ['a', 'e', 'i', 'o', 'u'];

        return in_array($firstLetter, $vowels) ? 'an' : 'a';
    }
}

// PHP Money Formatter
if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        // Check if the amount is an array
        if (is_array($amount)) {
            // Format each value in the array, casting to float, and join with a comma
            return implode(', ', array_map(function ($value) {
                return '£' . number_format((float)$value, 2, '.', ',');
            }, $amount));
        }

        // Format the single amount, casting to float
        return '£' . number_format((float)$amount, 2, '.', ',');
    }
} else {
    Log::error('formatCurrency function is not defined');
}

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
} else {
    // Log a notice instead of an error
    Log::notice('The a0rAn function was already defined.');
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
    // Log a notice instead of an error
    Log::notice('The formatCurrency function was already defined.');
}

if (!function_exists('cleanQuery')) {
    function cleanQuery($query)
    {
        // Trim whitespace
        $cleanQuery = trim($query);

        // Capitalize the first letter of each word
        $cleanQuery = ucwords(strtolower($cleanQuery));

        // Remove multiple spaces and keep only a single space between words
        $cleanQuery = preg_replace('/\s+/', ' ', $cleanQuery);

        return $cleanQuery;
    }
} else {
    // Log a notice instead of an error
    Log::notice('The cleanQuery function was already defined.');
}
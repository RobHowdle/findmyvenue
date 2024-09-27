<?php
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
        return '£' . number_format($amount, 2, '.', ',');
    }
}

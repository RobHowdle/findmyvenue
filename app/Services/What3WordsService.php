<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use What3words\Geocoder\Geocoder;
use What3words\Geocoder\AutoSuggestOption;

class What3WordsService
{
    protected $apiKey;

    // public function __construct()
    // {
    //     $this->apiKey = config('services.what3words.api_key');
    // }

    /**
     * Get What3Words address suggestions using autosuggest.
     *
     * @param string $words
     * @return array|null
     */
    // public function getSuggestedWords(string $words): ?array
    // {
    //     // Autosuggest API endpoint
    //     $response = Http::get('https://api.what3words.com/v3/autosuggest', [
    //         'input' => $words,
    //         'key' => $this->apiKey,
    //         'lang' => 'en',
    //         'country' => 'GB',
    //     ]);

    //     // Log the full response to inspect its structure
    //     Log::info('What3Words Autosuggest Response', [
    //         'response' => $response->json(),
    //         'status' => $response->status(),
    //     ]);

    //     if ($response->successful()) {
    //         $suggestions = $response->json('suggestions');
    //         Log::info('Suggestions', $suggestions);  // Log suggestions to see if they're being returned

    //         return is_array($suggestions) ? $suggestions : null;
    //     }

    //     Log::error('What3Words Autosuggest Error', [
    //         'response' => $response->json(),
    //         'status' => $response->status(),
    //     ]);

    //     return null;
    // }

    /**
     * Get What3Words address suggestions using autosuggest.
     *
     * @param string $words
     * @return array|null
     */
    public function getSuggestedWords(string $words): ?array
    {
        // Initialize Geocoder with the API key
        $api = new Geocoder(config('services.what3words.api_key'));

        // Define options for the API call
        $options = [
            AutoSuggestOption::clipTocountry("GB"),
            AutoSuggestOption::numberResults(5),
        ];

        // Call the autosuggest method using the correct Geocoder instance
        try {
            $response = $api->autosuggest($words, $options);

            // Check if suggestions exist
            if (isset($response['suggestions']) && is_array($response['suggestions'])) {
                return $response['suggestions'];
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }

        // Return null if no suggestions are found
        return null;
    }
}
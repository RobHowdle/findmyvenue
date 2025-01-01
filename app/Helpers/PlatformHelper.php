<?php

if (!function_exists('determinePlatform')) {
    /**
     * Determine the platform from a URL.
     *
     * @param string $url
     * @return string
     */
    function determinePlatform($url)
    {
        $host = parse_url($url, PHP_URL_HOST); // Extract the host from the URL
        $platforms = [
            'facebook.com' => 'facebook',
            'twitter.com' => 'twitter',
            'instagram.com' => 'instagram',
            'linkedin.com' => 'linkedin',
            'youtube.com' => 'youtube',
            'tiktok.com' => 'tiktok',
        ];

        // Match the host to a platform
        foreach ($platforms as $domain => $platform) {
            if (str_contains($host, $domain)) {
                return $platform;
            }
        }

        return 'unknown'; // Default to 'unknown' if no match is found
    }
}

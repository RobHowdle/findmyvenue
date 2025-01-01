<?php

namespace App\Helpers;

class SocialLinksHelper
{
    /**
     * Decode social links JSON and extract platforms and URLs.
     *
     * @param string|null $contactLink
     * @return array
     */
    public static function processSocialLinks($contactLink)
    {
        $platforms = [];

        if ($contactLink) {
            // Decode the JSON contact link field into an associative array
            $socialLinks = json_decode($contactLink, true);

            if (is_array($socialLinks)) {
                foreach ($socialLinks as $platform => $url) {
                    $normalizedPlatform = strtolower($platform);

                    $platforms[] = [
                        'url' => $url,
                        'platform' => $normalizedPlatform
                    ];
                }
            }
        }

        return $platforms;
    }
}
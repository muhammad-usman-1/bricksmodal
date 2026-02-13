<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\File;

class Bilingual
{
    /**
     * Get the bilingual label (English / Arabic).
     *
     * @param string $key
     * @param string $separator
     * @return string
     */
    public static function get($key, $separator = ' / ')
    {
        $en = Lang::get($key, [], 'en');

        // Determine context (onboarding or casting)
        $context = explode('.', $key)[0] ?? 'onboarding';

        // Load settings
        $settingsPath = resource_path('lang/label_settings.php');
        $settings = File::exists($settingsPath) ? include $settingsPath : ['onboarding' => true, 'casting' => true, 'talent' => true, 'admin_sidebar' => true, 'admin_home' => true, 'admin_talents' => true, 'admin_talent_profile' => true];

        // If context is disabled, return only English
        if (isset($settings[$context]) && $settings[$context] === false) {
            return $en;
        }

        $ar = Lang::get($key, [], 'ar');

        // If translations are identical (likely key not found for one), return just one
        if ($en === $key || $ar === $key) {
             return $en !== $key ? $en : ($ar !== $key ? $ar : $key);
        }
        
        if ($en === $ar) {
            return $en;
        }

        return $en . $separator . $ar;
    }
}

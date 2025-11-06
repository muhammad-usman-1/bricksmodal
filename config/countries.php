<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supported Countries for KWT SMS
    |--------------------------------------------------------------------------
    |
    | This array contains the list of countries supported by KWT SMS service.
    | Only these countries will be shown in the phone input dropdown for
    | talent registration and login.
    |
    | Format: 'country_code' => 'Country Name'
    |
    */

    'supported' => [
        'kw' => 'Kuwait',
        'sa' => 'Saudi Arabia',
        'ae' => 'United Arab Emirates',
        'bh' => 'Bahrain',
        'om' => 'Oman',
        'qa' => 'Qatar',
        'eg' => 'Egypt',
        'jo' => 'Jordan',
        'lb' => 'Lebanon',
        'sy' => 'Syria',
        'iq' => 'Iraq',
        'ye' => 'Yemen',
        'ps' => 'Palestine',
    ],

    /*
    |--------------------------------------------------------------------------
    | Preferred Countries
    |--------------------------------------------------------------------------
    |
    | These countries will appear at the top of the dropdown list
    |
    */

    'preferred' => ['kw', 'sa', 'ae', 'bh'],

    /*
    |--------------------------------------------------------------------------
    | Initial Country
    |--------------------------------------------------------------------------
    |
    | The default country to be selected when the page loads
    |
    */

    'initial' => 'kw',
];

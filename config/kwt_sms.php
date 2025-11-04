<?php

return [
    /*
    |--------------------------------------------------------------------------
    | KWT SMS supported countries
    |--------------------------------------------------------------------------
    |
    | A comma separated list of ISO 3166-1 alpha-2 country codes supported by
    | the KWT SMS provider. Example: 'kw,ae' This controls which countries
    | appear in the phone country dropdowns (registration/login).
    |
    */
    'countries' => array_filter(array_map('trim', explode(',', env('KWT_SMS_COUNTRIES', 'kw')))),
];

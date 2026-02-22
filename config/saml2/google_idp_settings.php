<?php

$this_idp_env_id = 'GOOGLE';

return [

    'strict' => true,

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Service Provider (Your Laravel App)
    |--------------------------------------------------------------------------
    */
    'sp' => [

        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',

        'x509cert' => env('SAML2_'.$this_idp_env_id.'_SP_x509', ''),
        'privateKey' => env('SAML2_'.$this_idp_env_id.'_SP_PRIVATEKEY', ''),

        'entityId' => env(
            'SAML2_'.$this_idp_env_id.'_SP_ENTITYID',
            env('APP_URL') . '/saml2/google/metadata'
        ),

        'assertionConsumerService' => [
            'url' => env(
                'SAML2_'.$this_idp_env_id.'_SP_ACS_URL',
                env('APP_URL') . '/saml2/google/acs'
            ),
        ],

        'singleLogoutService' => [
            'url' => env(
                'SAML2_'.$this_idp_env_id.'_SP_SLS_URL',
                env('APP_URL') . '/saml2/google/sls'
            ),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Identity Provider (Google)
    |--------------------------------------------------------------------------
    */
    'idp' => [

        'entityId' => env('SAML2_'.$this_idp_env_id.'_IDP_ENTITYID'),

        'singleSignOnService' => [
            'url' => env('SAML2_'.$this_idp_env_id.'_IDP_SSO_URL'),
        ],

        // Google usually does not require separate SLO URL
        'singleLogoutService' => [
            'url' => env('SAML2_'.$this_idp_env_id.'_IDP_SSO_URL'),
        ],

        // IMPORTANT: Certificate must be single line in .env
        'x509cert' => env('SAML2_'.$this_idp_env_id.'_IDP_x509'),

        // Disable fingerprint to avoid conflicts
        'certFingerprint' => '',
        'certFingerprintAlgorithm' => 'sha256',
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'security' => [

        'nameIdEncrypted' => false,
        'authnRequestsSigned' => false,
        'logoutRequestSigned' => false,
        'logoutResponseSigned' => false,
        'signMetadata' => false,

        'wantMessagesSigned' => false,
        'wantAssertionsSigned' => true,
        'wantNameIdEncrypted' => false,

        'requestedAuthnContext' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Contact Information
    |--------------------------------------------------------------------------
    */
    'contactPerson' => [

        'technical' => [
            'givenName' => env('APP_NAME', 'Laravel'),
            'emailAddress' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
        ],

        'support' => [
            'givenName' => 'Support',
            'emailAddress' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Organization Information
    |--------------------------------------------------------------------------
    */
    'organization' => [

        'en-US' => [
            'name' => env('APP_NAME', 'Laravel'),
            'displayname' => env('APP_NAME', 'Laravel'),
            'url' => env('APP_URL'),
        ],
    ],

];
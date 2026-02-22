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

        // Primary certificate (used as fallback for libs that only read x509cert)
        'x509cert' => 'MIIDdjCCAl6gAwIBAgIGAZjQzFZnMA0GCSqGSIb3DQEBCwUAMHwxFDASBgNVBAoTC0dvb2dsZSBJbmMuMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MQ8wDQYDVQQDEwZHb29nbGUxGTAXBgNVBAsTEEdvb2dsZSBXb3Jrc3BhY2UxCzAJBgNVBAYTAlVTMRMwEQYDVQQIEwpDYWxpZm9ybmlhMB4XDTI1MDgyMjA4MDE0OFoXDTMwMDgyMTA4MDE0OFowfDEUMBIGA1UEChMLR29vZ2xlIEluYy4xFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxDzANBgNVBAMTBkdvb2dsZTEZMBcGA1UECxMQR29vZ2xlIFdvcmtzcGFjZTELMAkGA1UEBhMCVVMxEzARBgNVBAgTCkNhbGlmb3JuaWEwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDzPZuurx8mapcSbzT/AeXVylSYO+QiOjXa5Y+0x8LidrcZ7SMaNm3zf7/ZWLqS/LdbEM2KFCny1+q8THBZUpJTFKMReqOS8P0KZnFUaAKFICd4lHRIyjwgBqV8wamDiHvG2DHjyRxBCzL8ajN5mZojq4gjNGny1ktqOlcbcmGq1opA7xaxsDTwArl802zuxkCzxhMhb8CHS7XwBCn7pI04EToNstsBy/7WZa16WTlxEk7OPfUiat0Oxjl/P9cp4cKoN46UIQkCySioLbY+J2Uu/xOn1j0IkSPvMhjTAd6BAchGXdpU71Bh5QHaIzXm9Qw58KzypA5SOs97r8z1i42BAgMBAAEwDQYJKoZIhvcNAQELBQADggEBAH6Xp3L6E5r43v4lcEijvNoG471EeLr2DyOQiBN5biIWdKvMGa7CBhh4wEcw5If5Ve7X9aIrPLCT76Dvlu8Sms7M3R2ZKotSibhi3Eb2mLkZP4UZamusgI8JgkjXS0qJtvLQK/wTwBQf9xQ9bVdBQAV0wtwNrKvi7PKCg9bmqCRkHaLSPBqOrzs0D6bnsb+DNLATAGihwnEHA/Ror606E4s49VdfjH/IbN9or0f0Q6uAPvEc9qjbNualeHB9uXwWYa1oGJ2h7GSyGY8HM7BqsAFMd2y87bvJJvfqtOW5GJXNrbzDkEzsYYr8isPYyFSetGxNnIQPKD5FcQLfckFmhAg=',

        // Both Google signing certificates — Google may sign with either during key rotation
        'x509certMulti' => [
            'signing' => [
                // Cert 1: primary (issued 2025-08-22 08:01:48, expires 2030-08-21)
                'MIIDdjCCAl6gAwIBAgIGAZjQzFZnMA0GCSqGSIb3DQEBCwUAMHwxFDASBgNVBAoTC0dvb2dsZSBJbmMuMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MQ8wDQYDVQQDEwZHb29nbGUxGTAXBgNVBAsTEEdvb2dsZSBXb3Jrc3BhY2UxCzAJBgNVBAYTAlVTMRMwEQYDVQQIEwpDYWxpZm9ybmlhMB4XDTI1MDgyMjA4MDE0OFoXDTMwMDgyMTA4MDE0OFowfDEUMBIGA1UEChMLR29vZ2xlIEluYy4xFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxDzANBgNVBAMTBkdvb2dsZTEZMBcGA1UECxMQR29vZ2xlIFdvcmtzcGFjZTELMAkGA1UEBhMCVVMxEzARBgNVBAgTCkNhbGlmb3JuaWEwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDzPZuurx8mapcSbzT/AeXVylSYO+QiOjXa5Y+0x8LidrcZ7SMaNm3zf7/ZWLqS/LdbEM2KFCny1+q8THBZUpJTFKMReqOS8P0KZnFUaAKFICd4lHRIyjwgBqV8wamDiHvG2DHjyRxBCzL8ajN5mZojq4gjNGny1ktqOlcbcmGq1opA7xaxsDTwArl802zuxkCzxhMhb8CHS7XwBCn7pI04EToNstsBy/7WZa16WTlxEk7OPfUiat0Oxjl/P9cp4cKoN46UIQkCySioLbY+J2Uu/xOn1j0IkSPvMhjTAd6BAchGXdpU71Bh5QHaIzXm9Qw58KzypA5SOs97r8z1i42BAgMBAAEwDQYJKoZIhvcNAQELBQADggEBAH6Xp3L6E5r43v4lcEijvNoG471EeLr2DyOQiBN5biIWdKvMGa7CBhh4wEcw5If5Ve7X9aIrPLCT76Dvlu8Sms7M3R2ZKotSibhi3Eb2mLkZP4UZamusgI8JgkjXS0qJtvLQK/wTwBQf9xQ9bVdBQAV0wtwNrKvi7PKCg9bmqCRkHaLSPBqOrzs0D6bnsb+DNLATAGihwnEHA/Ror606E4s49VdfjH/IbN9or0f0Q6uAPvEc9qjbNualeHB9uXwWYa1oGJ2h7GSyGY8HM7BqsAFMd2y87bvJJvfqtOW5GJXNrbzDkEzsYYr8isPYyFSetGxNnIQPKD5FcQLfckFmhAg=',
                // Cert 2: secondary (issued 2025-08-22 09:33:27, expires 2030-08-21)
                'MIIDdjCCAl6gAwIBAgIGAZjRIDzHMA0GCSqGSIb3DQEBCwUAMHwxFDASBgNVBAoTC0dvb2dsZSBJbmMuMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MQ8wDQYDVQQDEwZHb29nbGUxGTAXBgNVBAsTEEdvb2dsZSBXb3Jrc3BhY2UxCzAJBgNVBAYTAlVTMRMwEQYDVQQIEwpDYWxpZm9ybmlhMB4XDTI1MDgyMjA5MzMyN1oXDTMwMDgyMTA5MzMyN1owfDEUMBIGA1UEChMLR29vZ2xlIEluYy4xFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxDzANBgNVBAMTBkdvb2dsZTEZMBcGA1UECxMQR29vZ2xlIFdvcmtzcGFjZTELMAkGA1UEBhMCVVMxEzARBgNVBAgTCkNhbGlmb3JuaWEwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDaylNXQV4LrpsyX1xpWdCpn/7Q7xEGicdID0+D69lOhUAwiVCht/nILRqt2oOnXxXuwIceeCJ4DFau2rYCzqTbax5ebMXqE/Gp39K/vLX0hpNsuoKBEMZiXjgyDxMHKu5lJvUHlZgxOSV9JHXhcTS6OS9vfVh7rDIOA9QmgP+IiyV80pp8fNFQOEjpprFR9yuAUUVpU0hSXy0m1d36lJY2eEPTCh6Yvdj+rF7RlrirJMRhufzlwKGr9OHrvjFdF6Ap0qXioewhER4YJYt8oR9g3aMIAvRkMWJUklrIDN8/+/LG4EuZ/RHaznnm727Aj/bfBBHfZ08TkmSv0olO3/cXAgMBAAEwDQYJKoZIhvcNAQELBQADggEBALbrDUyDc3CCNREutIP1tUthdKgsSJE7kHok0Ho+ZjvS2OH0ghsN7shWI5bFYtMe/daWzlFdD5C3ENlXT2o72p6jHhk4aw5vrkxBpsmMuIgdNrOmfWv9rPAgW938tLeh5NqOzL05cr6we/nWvOogRcHQZAqC90QNHOQwQEyO714dfhhJsI9cY22hqjXYbqHf3MLH2KrsseMrAJ/ZJTfZtSWSgVdT6OGvZ8fDkhw4nNRIoNzk6NTU0fa8F4A3tK85AhjX44YUC2MqyWKpMK1T+88PJAk42Vou1hHQRLsTZe7tweef2G6WH5JfQRIb5ShoWWreO/gV5BooV5vn8Pxp2OQ=',
            ],
        ],

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
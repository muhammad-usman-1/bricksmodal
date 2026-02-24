<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SAML2 Google SSO Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file is for Google SAML SSO integration.
    | You need to configure these values based on your Google Workspace
    | SAML application settings.
    |
    */

    // SP (Service Provider) - Your Laravel Application
    'sp' => [
        'entityId' => env('SAML_SP_ENTITY_ID', env('APP_URL') . '/admin/login/saml/metadata'),
        'assertionConsumerService' => [
            'url' => env('SAML_SP_ACS_URL', env('APP_URL') . '/admin/login/saml/acs'),
        ],
        'singleLogoutService' => [
            'url' => env('SAML_SP_SLS_URL', env('APP_URL') . '/admin/login/saml/sls'),
        ],
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
        'x509cert' => env('SAML_SP_CERT', ''),
        'privateKey' => env('SAML_SP_PRIVATE_KEY', ''),
    ],

    // IdP (Identity Provider) - Google Workspace
    'idp' => [
        'entityId' => env('SAML_IDP_ENTITY_ID', 'https://accounts.google.com/o/saml2?idpid=' . env('SAML_GOOGLE_IDP_ID', '')),
        'singleSignOnService' => [
            'url' => env('SAML_IDP_SSO_URL', 'https://accounts.google.com/o/saml2?idpid=' . env('SAML_GOOGLE_IDP_ID', '')),
        ],
        'singleLogoutService' => [
            'url' => env('SAML_IDP_SLS_URL', 'https://accounts.google.com/o/saml2?idpid=' . env('SAML_GOOGLE_IDP_ID', '')),
        ],
        'x509cert' => env('SAML_IDP_CERT', ''),
        'certFingerprint' => env('SAML_IDP_CERT_FINGERPRINT', ''),
        'certFingerprintAlgorithm' => env('SAML_IDP_CERT_FINGERPRINT_ALGORITHM', 'sha256'),
    ],

    // Security settings
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
        'signatureAlgorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
        'digestAlgorithm' => 'http://www.w3.org/2001/04/xmlenc#sha256',
    ],

    // Contact information
    'contactPerson' => [
        'technical' => [
            'givenName' => env('SAML_CONTACT_TECHNICAL_NAME', 'Technical Support'),
            'emailAddress' => env('SAML_CONTACT_TECHNICAL_EMAIL', env('MAIL_FROM_ADDRESS')),
        ],
        'support' => [
            'givenName' => env('SAML_CONTACT_SUPPORT_NAME', 'Support'),
            'emailAddress' => env('SAML_CONTACT_SUPPORT_EMAIL', env('MAIL_FROM_ADDRESS')),
        ],
    ],

    // Organization information
    'organization' => [
        'en' => [
            'name' => env('SAML_ORG_NAME', env('APP_NAME')),
            'displayname' => env('SAML_ORG_DISPLAY_NAME', env('APP_NAME')),
            'url' => env('SAML_ORG_URL', env('APP_URL')),
        ],
    ],
];

<?php

// Environment variable prefix for this IdP
$this_idp_env_id = 'GOOGLE';

return $settings = array(

    // If 'strict' is True, then the PHP Toolkit will reject unsigned
    // or unencrypted messages if it expects them signed or encrypted
    // Also will reject the messages if not strictly follow the SAML
    // standard: Destination, NameId, Conditions ... are validated too.
    'strict' => true,

    // Enable debug mode (to print errors)
    'debug' => env('APP_DEBUG', false),

    // Service Provider Data that we are deploying
    'sp' => array(

        // Specifies constraints on the name identifier to be used to
        // represent the requested subject.
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',

        // Usually x509cert and privateKey of the SP are provided by files placed at
        // the certs folder. But we can also provide them with the following parameters
        'x509cert' => env('SAML2_'.$this_idp_env_id.'_SP_x509', ''),
        'privateKey' => env('SAML2_'.$this_idp_env_id.'_SP_PRIVATEKEY', ''),

        // Identifier (URI) of the SP entity.
        // Leave blank to use the '{idpName}_metadata' route, e.g. 'google_metadata'.
        'entityId' => env('SAML2_'.$this_idp_env_id.'_SP_ENTITYID', env('APP_URL') . '/saml2/google/metadata'),

        // Specifies info about where and how the <AuthnResponse> message MUST be
        // returned to the requester, in this case our SP.
        'assertionConsumerService' => array(
            // URL Location where the <Response> from the IdP will be returned,
            // using HTTP-POST binding.
            // Leave blank to use the '{idpName}_acs' route, e.g. 'google_acs'
            'url' => env('SAML2_'.$this_idp_env_id.'_SP_ACS_URL', env('APP_URL') . '/saml2/google/acs'),
        ),
        // Specifies info about where and how the <Logout Response> message MUST be
        // returned to the requester, in this case our SP.
        'singleLogoutService' => array(
            // URL Location where the <Response> from the IdP will be returned,
            // using HTTP-Redirect binding.
            'url' => env('SAML2_'.$this_idp_env_id.'_SP_SLS_URL', env('APP_URL') . '/saml2/google/sls'),
        ),
    ),

    // Identity Provider Data that we want connect with our SP
    'idp' => array(
        // Identifier of the IdP entity (must be a URI)
        // From your Google SAML configuration image
        'entityId' => env('SAML2_'.$this_idp_env_id.'_IDP_ENTITYID', 'https://accounts.google.com/o/saml2?idpid=C03i43034'),
        
        // SSO endpoint info of the IdP. (Authentication Request protocol)
        'singleSignOnService' => array(
            // URL Target of the IdP where the SP will send the Authentication Request Message,
            // using HTTP-Redirect binding.
            // From your Google SAML configuration image
            'url' => env('SAML2_'.$this_idp_env_id.'_IDP_SSO_URL', 'https://accounts.google.com/o/saml2/idp?idpid=C03i43034'),
        ),
        
        // SLO endpoint info of the IdP.
        'singleLogoutService' => array(
            // URL Location of the IdP where the SP will send the SLO Request,
            // using HTTP-Redirect binding.
            'url' => env('SAML2_'.$this_idp_env_id.'_IDP_SL_URL', 'https://accounts.google.com/o/saml2/idp?idpid=C03i43034'),
        ),
        
        // Public x509 certificate of the IdP
        // You need to copy the full certificate from Google SAML configuration page
        // It should start with -----BEGIN CERTIFICATE----- and end with -----END CERTIFICATE-----
        'x509cert' => env('SAML2_'.$this_idp_env_id.'_IDP_x509', ''),
        
        /*
         *  Instead of use the whole x509cert you can use a fingerprint
         *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it)
         *  From your Google SAML configuration image
         */
        'certFingerprint' => env('SAML2_'.$this_idp_env_id.'_IDP_CERT_FINGERPRINT', '44:56:D9:DA:F3:83:21:B5:41:00:31:16:7A:02:B9:F5:EE:80:A1:6B:A6:E3:50:C3:EC:BD:42:0B:8C:15:55:08'),
        'certFingerprintAlgorithm' => 'sha256',
    ),

    // Security settings
    'security' => array(

        /** signatures and encryptions offered */

        // Indicates that the nameID of the <samlp:logoutRequest> sent by this SP
        // will be encrypted.
        'nameIdEncrypted' => false,

        // Indicates whether the <samlp:AuthnRequest> messages sent by this SP
        // will be signed.              [The Metadata of the SP will offer this info]
        'authnRequestsSigned' => false,

        // Indicates whether the <samlp:logoutRequest> messages sent by this SP
        // will be signed.
        'logoutRequestSigned' => false,

        // Indicates whether the <samlp:logoutResponse> messages sent by this SP
        // will be signed.
        'logoutResponseSigned' => false,

        /* Sign the Metadata */
        'signMetadata' => false,

        /** signatures and encryptions required **/

        // Indicates a requirement for the <samlp:Response>, <samlp:LogoutRequest> and
        // <samlp:LogoutResponse> elements received by this SP to be signed.
        'wantMessagesSigned' => false,

        // Indicates a requirement for the <saml:Assertion> elements received by
        // this SP to be signed.        [The Metadata of the SP will offer this info]
        'wantAssertionsSigned' => true,

        // Indicates a requirement for the NameID received by
        // this SP to be encrypted.
        'wantNameIdEncrypted' => false,

        // Authentication context.
        'requestedAuthnContext' => true,
    ),

    // Contact information template
    'contactPerson' => array(
        'technical' => array(
            'givenName' => env('APP_NAME', 'Laravel'),
            'emailAddress' => env('MAIL_FROM_ADDRESS', 'no@reply.com')
        ),
        'support' => array(
            'givenName' => 'Support',
            'emailAddress' => env('MAIL_FROM_ADDRESS', 'no@reply.com')
        ),
    ),

    // Organization information template
    'organization' => array(
        'en-US' => array(
            'name' => env('APP_NAME', 'Laravel'),
            'displayname' => env('APP_NAME', 'Laravel'),
            'url' => env('APP_URL', 'http://localhost')
        ),
    ),

);

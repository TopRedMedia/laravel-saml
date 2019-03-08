<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SAML
    |--------------------------------------------------------------------------
    |
    | Setting for the saml package
    |
    */

    // This is the route prefix added to the base path for every SAML endpoint routes
    'route_prefix' => '/saml',

    // ISPs are the service providers, where users will be authenticated. This package supports multiple ISPs, each
    // available under a different route.
    'endpoints' => [

        // Each ISP that we will connect, identified by a key. The key is used in the url.
        'google' => [

            // Service Provider Data that we are deploying
            'sp' => [
                // Usually x509cert and privateKey of the SP are provided by files placed at
                // the certs folder. But we can also provide them with the following parameters
                'x509cert' => '',
                'privateKey' => '',
            ],

            // Identity Provider Data that we want connect with our SP
            'idp' => [
                // Identifier of the IdP entity  (must be a URI)
                'entityId' => "https://accounts.google.com/o/saml2?idpid=C03fimywe",

                // SSO endpoint info of the IdP. (Authentication Request protocol)
                'singleSignOnService' => [
                    // URL Target of the IdP where the SP will send the Authentication Request Message,
                    // using HTTP-Redirect binding.
                    'url' => 'https://accounts.google.com/o/saml2/idp?idpid=C03fimywe',
                ],

                // Public x509 certificate of the IdP
                'x509cert' => 'MIIDdDCCAlygAwIBAgIGAWlSWJf5MA0GCSqGSIb3DQEBCwUAMHsxFDASBgNVBAoTC0dvb2dsZSBJbmMuMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MQ8wDQYDVQQDEwZHb29nbGUxGDAWBgNVBAsTD0dvb2dsZSBGb3IgV29yazELMAkGA1UEBhMCVVMxEzARBgNVBAgTCkNhbGlmb3JuaWEwHhcNMTkwMzA2MDkzMjExWhcNMjQwMzA0MDkzMjExWjB7MRQwEgYDVQQKEwtHb29nbGUgSW5jLjEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEPMA0GA1UEAxMGR29vZ2xlMRgwFgYDVQQLEw9Hb29nbGUgRm9yIFdvcmsxCzAJBgNVBAYTAlVTMRMwEQYDVQQIEwpDYWxpZm9ybmlhMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAkFHlHAs3DzooHNiwmIZmqkb3xOmi8humrYDbtuZSAvvIjylgqg0cEeKHQwQCAM31CeJIfLHnk0Cs5aZiK1BjG0WssdlbG81MtIBwNusJJXkG/CIFrSvLEIvOiaD5CT+tOyIvIQS0vVvzEj7v0TWW+pkC2D6aehazjAM/sXrszT0TkIqTHqrfxInYAfo8BZyRrbn6/tQ6icK5jMJA5mjs/2poLsg39gCBgTR1SL6JhIf75b7X4H1FcqsZEjJtADKMON5cMK3O0hgiUCHh4a53yC7ia8vAHc3TY770Q+u6AyWHAq9I2dHRKQU1/CCF+8enHuBsdYGgl+1+gTfNroSMnQIDAQABMA0GCSqGSIb3DQEBCwUAA4IBAQB0YW4jnebUF7shdmk74Rwmur5uiDtnsAx/5QG7w+CGNu3moM84MBsOLbID+WTL9ILsmpvw8vbrFXUIDSFikx9UdguyaUKR2cGaCiedwU7Eiqnj2GHQ6QeMKA1grTS1yH0mixfMo+NeeSVbyaJdxgw97HD7FcIyqrXfHA6YYJ0feS/FaP6lG84I3UxL6q8Y2YuS9AxKj1j+Tx7L5XbBqAIduIGfoFtixnqAMdg/+oqcJghlgOYnq2gJ7weBceJ5PZXczp9gbDXdTqGM2l+nz4HC9Tcuw7EhhMQ7KtW0J4gpb10/Vcv2KW2kjS4F4uqvIQEZ2T+ZzpddhTxKMvTUV4ib',
            ],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    |
    | These settings will be array_merged into one array and given to the oneclick
    | library. All of these keys can be overridenn in the ISP settings above.
    |
    */
    'default' => [
        // If 'strict' is True, then the PHP Toolkit will reject unsigned
        // or unencrypted messages if it expects them signed or encrypted
        // Also will reject the messages if not strictly follow the SAML
        // standard: Destination, NameId, Conditions ... are validated too.
        'strict' => true,

        // Enable debug mode (to print errors)
        'debug' => env('APP_DEBUG', false),

        // If 'proxyVars' is True, then the Saml lib will trust proxy headers
        // e.g X-Forwarded-Proto / HTTP_X_FORWARDED_PROTO. This is useful if
        // your application is running behind a load balancer which terminates
        // SSL.
        'proxyVars' => false,

        // Indicates how the parameters will be
        // retrieved from the sls request for signature validation
        'retrieveParametersFromServer' => false,

        // Service Provider Data that we are deploying
        'sp' => [
            // Specifies constraints on the name identifier to be used to
            // represent the requested subject.
            // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported
            'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
        ],

        // Identity Provider Data that we want connect with our SP
        'idp' => [
            // SLO endpoint info of the IdP.
            'singleLogoutService' => [
                // URL Location of the IdP where the SP will send the SLO Request,
                // using HTTP-Redirect binding.
                'url' => '',
            ],
        ],


        // Which route to show on error messages
        'errorRoute' => '/route/to/display/error',

        // Where to redirect after logout
        'logoutRoute' => '/route/after/logout',

        // Security settings
        'security' => [
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

            // Sign the Metadata
            'signMetadata' => false,

            // Indicates a requirement for the <samlp:Response>, <samlp:LogoutRequest> and
            // <samlp:LogoutResponse> elements received by this SP to be signed.
            'wantMessagesSigned' => false,

            // Indicates a requirement for the <saml:Assertion> elements received by
            // this SP to be signed.        [The Metadata of the SP will offer this info]
            'wantAssertionsSigned' => false,

            // Indicates a requirement for the NameID received by
            // this SP to be encrypted.
            'wantNameIdEncrypted' => false,

            // Authentication context.
            // Set to false and no AuthContext will be sent in the AuthNRequest,
            // Set true or don't present thi parameter and you will get an AuthContext 'exact' 'urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport'
            // Set an array with the possible auth context values: array ('urn:oasis:names:tc:SAML:2.0:ac:classes:Password', 'urn:oasis:names:tc:SAML:2.0:ac:classes:X509'),
            'requestedAuthnContext' => true,
        ],

        // Contact information template, it is recommended to suply a technical and support contacts
        'contactPerson' => [
            'technical' => [
                'givenName' => 'name',
                'emailAddress' => 'no@reply.com'
            ],
            'support' => [
                'givenName' => 'Support',
                'emailAddress' => 'no@reply.com'
            ],
        ],

        // Organization information template, the info in en_US lang is recomended, add more if required
        'organization' => [
            'en-US' => [
                'name' => 'Name',
                'displayname' => 'Display Name',
                'url' => 'http://url'
            ],
        ],
    ],

];

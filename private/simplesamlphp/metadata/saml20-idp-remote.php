<?php

/**
 * SAML 2.0 remote IdP metadata for SimpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-remote
 */
if(isset($_ENV['PANTHEON_ENVIRONMENT'])){
    switch ($_ENV['PANTHEON_ENVIRONMENT']) {
        case (str_starts_with($_ENV['PANTHEON_ENVIRONMENT'], 'ping-dev')):
            $metadata['uisping_entity'] = [
                'entityid' => 'uisping_entity',
                'contacts' => [
                    [
                        'contactType' => 'administrative',
                        'company' => 'University of Colorado System',
                        'givenName' => 'Kirk',
                        'surName' => 'Walker',
                        'emailAddress' => [
                            'uisiamdevelopers@ad.cu.edu',
                        ],
                    ],
                ],
                'metadata-set' => 'saml20-idp-remote',
                'sign.authnrequest' => true,
                'SingleSignOnService' => [
                    [
                        'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
                        'Location' => 'https://ping.prod.cu.edu/idp/SSO.saml2',
                    ],
                    [
                        'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
                        'Location' => 'https://ping.prod.cu.edu/idp/SSO.saml2',
                    ],
                ],
                'SingleLogoutService' => [],
                'ArtifactResolutionService' => [],
                'NameIDFormats' => [
                    'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
                ],
                'keys' => [
                    [
                        'encryption' => false,
                        'signing' => true,
                        'type' => 'X509Certificate',
                        'X509Certificate' => 'MIIDrjCCApagAwIBAgIGAX+KyBUxMA0GCSqGSIb3DQEBCwUAMIGXMQswCQYDVQQGEwJVUzERMA8GA1UECBMIQ29sb3JhZG8xHzAdBgNVBAoTFlVuaXZlcnNpdHkgb2YgQ29sb3JhZG8xKDAmBgNVBAsTH1VuaXZlcnNpdHkgSW5mb3JtYXRpb24gU2VydmljZXMxKjAoBgNVBAMTIXBuZy1wcm9kLXNhbWwtc2lnbmluZy5wcm9kLmN1LmVkdTAeFw0yMjAzMTQyMzM0MDJaFw0zMjAzMTIwMDM0MDJaMIGXMQswCQYDVQQGEwJVUzERMA8GA1UECBMIQ29sb3JhZG8xHzAdBgNVBAoTFlVuaXZlcnNpdHkgb2YgQ29sb3JhZG8xKDAmBgNVBAsTH1VuaXZlcnNpdHkgSW5mb3JtYXRpb24gU2VydmljZXMxKjAoBgNVBAMTIXBuZy1wcm9kLXNhbWwtc2lnbmluZy5wcm9kLmN1LmVkdTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALr4h7ZXlqlndNCqMSrOacBvquboeHePjAcmbMvPXEjKOhqftQ+KqYGlzg0+37MvT5f779at+X3lsiGc2dvTHUESAhy/Tni+RXMekB/J9+KVVfz6PLfAHjWrBBgWYlVl7x6ck+NZEp+A2ySgkKJNoVDXx33lI0MphNliUmQ1BW2DFJlUkkq+wI3cwj7R/kllsjW+9Cdzos9/XSByd/lZ//Um1sz362RU2UJiWKjs9sn4x5gLBMfBAxt88NbTOdnfGaf42jiPjSvsva8iXP3s9THigsi+QWldHCyuWXItrKGL3C/bVNsyaA5AW/AaxSsGNMiI2QYXaUXW87cMiGL9YUkCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAHgPSkafiZN+6Hl07qMa+NtYH6qPbfukGtolAs+cwdI659ibZcYb721rSinJjEYYM+mxXDba8v6S3cC9HAJwZpGX5KZhgvfCaOHfIU7PJhmrJRnZkFWfIc4j2ej68cRUBgexceuoSeJboBkqaWUCMIyxFokSyR7RvkOVhd6y59sJTERDwwvAqwZk3b3EijspZJIkE8D1kPAK9+tmZTsReR7dTTz+gGx07WA6xCI0mel97OT7Ggi9TIqKSLVpFvBBYU8PaZnk+Ia8yIF3DuhUWZrzZIc667907kXoL2bJHOPei24RH+8lcNrbydL51I4UzYikHZI0Ard3a57fqiPXJTw==',
                    ],
                ],
            ];
        case (str_starts_with($_ENV['PANTHEON_ENVIRONMENT'], 'ping-test')):
            $metadata['uispingtst_entity'] = [
                'entityid' => 'uispingtst_entity',
                'contacts' => [
                    [
                        'contactType' => 'administrative',
                        'company' => 'CU UIS',
                        'givenName' => 'Kirk',
                        'surName' => 'Walker',
                        'emailAddress' => [
                            'kirk.walker@cu.edu',
                        ],
                        'telephoneNumber' => [
                            '303-860-4331',
                        ],
                    ],
                ],
                'metadata-set' => 'saml20-idp-remote',
                'sign.authnrequest' => true,
                'SingleSignOnService' => [
                    [
                        'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
                        'Location' => 'https://pngtst.qa.cu.edu/idp/SSO.saml2',
                    ],
                    [
                        'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
                        'Location' => 'https://pngtst.qa.cu.edu/idp/SSO.saml2',
                    ],
                ],
                'SingleLogoutService' => [],
                'ArtifactResolutionService' => [],
                'NameIDFormats' => [
                    'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
                ],
                'keys' => [
                    [
                        'encryption' => false,
                        'signing' => true,
                        'type' => 'X509Certificate',
                        'X509Certificate' => 'MIIDwDCCAqigAwIBAgIGAVs0f1JBMA0GCSqGSIb3DQEBCwUAMIGgMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ08xDzANBgNVBAcTBkRlbnZlcjEfMB0GA1UEChMWVW5pdmVyc2l0eSBvZiBDb2xvcmFkbzEsMCoGA1UECxMjVW5pdmVyc2l0eSBJbmZvcm1hdGlvbiBTeXN0ZW1zIChjdSkxJDAiBgNVBAMTG3BuZ3RzdHNhbWxzaWduaW5nLnFhLmN1LmVkdTAeFw0xNzA0MDMxNTQ2NTFaFw0yNzA0MDExNTQ2NTFaMIGgMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ08xDzANBgNVBAcTBkRlbnZlcjEfMB0GA1UEChMWVW5pdmVyc2l0eSBvZiBDb2xvcmFkbzEsMCoGA1UECxMjVW5pdmVyc2l0eSBJbmZvcm1hdGlvbiBTeXN0ZW1zIChjdSkxJDAiBgNVBAMTG3BuZ3RzdHNhbWxzaWduaW5nLnFhLmN1LmVkdTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIhlIjULlZAyrGrg/baj4ICtmOcOdF/FPss+y5icUKYuKOgO+rB9p8SqaPsEUZAS/CxjYuBj/8ulnFaMVQlJZEPGcyPZhONUEYVuNs9bP/bZPr2u6zlqqxYP4o7NE6EU0SztNaTk6YEXKcUOPR5cOhuJc0D/bd40fCn/+pvYJFeuNoDz6OksH9KB+upJTbhHx4xaEN5J352mNzt0LFW44rw0GiK+MDSI+FtNFx4hfSmuwZBCCY3QoRv4MVpFsDUw5V3+Ugi6ocjgMI1K1c0ZrjD4n6Fr/b2t2yeSoqlPXIkzfZzaGi+cTQm1DUhin+dX3pK0NhmAtWzy6d9+aKL3qI8CAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAcF22ETbsqAzW7a+cgwkVcYDO8NFoegQkwto9vXaSoZgctaaIlE6UgcQk0CuWW/mvOundIgXpzuBWfjU2XxpNJg4VVJqFaKFJU9FrCNylSdBiehBHjcqWXO7ONszAv2cPYzcy1C6kG6YYQOZK7QMs3kc+XdAjxFHs8Baty+CoOeUJqMFlq8noMZYIS9bL7SjtiTQCmxuRaE8wx47X9eOZQNvRrnOEwnm6iMB9v07A0N/LMT873RMsVdWhXVwiYADHH9GYipG+XSNIX4l0wnEuFMd6RPJB6KpB3GcOANovTDNWdw6hG/sAMQD1HuQx+Dw5VNYYV8RnuakmUHa8Lp3IOw==',
                    ],
                ],
            ];
            break;
        default:
            $metadata['uisping_entity'] = [
                'entityid' => 'uisping_entity',
                'contacts' => [
                    [
                        'contactType' => 'administrative',
                        'company' => 'University of Colorado System',
                        'givenName' => 'Kirk',
                        'surName' => 'Walker',
                        'emailAddress' => [
                            'uisiamdevelopers@ad.cu.edu',
                        ],
                    ],
                ],
                'metadata-set' => 'saml20-idp-remote',
                'sign.authnrequest' => true,
                'SingleSignOnService' => [
                    [
                        'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
                        'Location' => 'https://ping.prod.cu.edu/idp/SSO.saml2',
                    ],
                    [
                        'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
                        'Location' => 'https://ping.prod.cu.edu/idp/SSO.saml2',
                    ],
                ],
                'SingleLogoutService' => [],
                'ArtifactResolutionService' => [],
                'NameIDFormats' => [
                    'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
                ],
                'keys' => [
                    [
                        'encryption' => false,
                        'signing' => true,
                        'type' => 'X509Certificate',
                        'X509Certificate' => 'MIIDrjCCApagAwIBAgIGAX+KyBUxMA0GCSqGSIb3DQEBCwUAMIGXMQswCQYDVQQGEwJVUzERMA8GA1UECBMIQ29sb3JhZG8xHzAdBgNVBAoTFlVuaXZlcnNpdHkgb2YgQ29sb3JhZG8xKDAmBgNVBAsTH1VuaXZlcnNpdHkgSW5mb3JtYXRpb24gU2VydmljZXMxKjAoBgNVBAMTIXBuZy1wcm9kLXNhbWwtc2lnbmluZy5wcm9kLmN1LmVkdTAeFw0yMjAzMTQyMzM0MDJaFw0zMjAzMTIwMDM0MDJaMIGXMQswCQYDVQQGEwJVUzERMA8GA1UECBMIQ29sb3JhZG8xHzAdBgNVBAoTFlVuaXZlcnNpdHkgb2YgQ29sb3JhZG8xKDAmBgNVBAsTH1VuaXZlcnNpdHkgSW5mb3JtYXRpb24gU2VydmljZXMxKjAoBgNVBAMTIXBuZy1wcm9kLXNhbWwtc2lnbmluZy5wcm9kLmN1LmVkdTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALr4h7ZXlqlndNCqMSrOacBvquboeHePjAcmbMvPXEjKOhqftQ+KqYGlzg0+37MvT5f779at+X3lsiGc2dvTHUESAhy/Tni+RXMekB/J9+KVVfz6PLfAHjWrBBgWYlVl7x6ck+NZEp+A2ySgkKJNoVDXx33lI0MphNliUmQ1BW2DFJlUkkq+wI3cwj7R/kllsjW+9Cdzos9/XSByd/lZ//Um1sz362RU2UJiWKjs9sn4x5gLBMfBAxt88NbTOdnfGaf42jiPjSvsva8iXP3s9THigsi+QWldHCyuWXItrKGL3C/bVNsyaA5AW/AaxSsGNMiI2QYXaUXW87cMiGL9YUkCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAHgPSkafiZN+6Hl07qMa+NtYH6qPbfukGtolAs+cwdI659ibZcYb721rSinJjEYYM+mxXDba8v6S3cC9HAJwZpGX5KZhgvfCaOHfIU7PJhmrJRnZkFWfIc4j2ej68cRUBgexceuoSeJboBkqaWUCMIyxFokSyR7RvkOVhd6y59sJTERDwwvAqwZk3b3EijspZJIkE8D1kPAK9+tmZTsReR7dTTz+gGx07WA6xCI0mel97OT7Ggi9TIqKSLVpFvBBYU8PaZnk+Ia8yIF3DuhUWZrzZIc667907kXoL2bJHOPei24RH+8lcNrbydL51I4UzYikHZI0Ard3a57fqiPXJTw==',
                    ],
                ],
            ];
            break;
    }
} else {
    $metadata['uisping_entity'] = [
        'entityid' => 'uisping_entity',
        'contacts' => [
            [
                'contactType' => 'administrative',
                'company' => 'University of Colorado System',
                'givenName' => 'Kirk',
                'surName' => 'Walker',
                'emailAddress' => [
                    'uisiamdevelopers@ad.cu.edu',
                ],
            ],
        ],
        'metadata-set' => 'saml20-idp-remote',
        'sign.authnrequest' => true,
        'SingleSignOnService' => [
            [
                'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
                'Location' => 'https://ping.prod.cu.edu/idp/SSO.saml2',
            ],
            [
                'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
                'Location' => 'https://ping.prod.cu.edu/idp/SSO.saml2',
            ],
        ],
        'SingleLogoutService' => [],
        'ArtifactResolutionService' => [],
        'NameIDFormats' => [
            'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
        ],
        'keys' => [
            [
                'encryption' => false,
                'signing' => true,
                'type' => 'X509Certificate',
                'X509Certificate' => 'MIIDrjCCApagAwIBAgIGAX+KyBUxMA0GCSqGSIb3DQEBCwUAMIGXMQswCQYDVQQGEwJVUzERMA8GA1UECBMIQ29sb3JhZG8xHzAdBgNVBAoTFlVuaXZlcnNpdHkgb2YgQ29sb3JhZG8xKDAmBgNVBAsTH1VuaXZlcnNpdHkgSW5mb3JtYXRpb24gU2VydmljZXMxKjAoBgNVBAMTIXBuZy1wcm9kLXNhbWwtc2lnbmluZy5wcm9kLmN1LmVkdTAeFw0yMjAzMTQyMzM0MDJaFw0zMjAzMTIwMDM0MDJaMIGXMQswCQYDVQQGEwJVUzERMA8GA1UECBMIQ29sb3JhZG8xHzAdBgNVBAoTFlVuaXZlcnNpdHkgb2YgQ29sb3JhZG8xKDAmBgNVBAsTH1VuaXZlcnNpdHkgSW5mb3JtYXRpb24gU2VydmljZXMxKjAoBgNVBAMTIXBuZy1wcm9kLXNhbWwtc2lnbmluZy5wcm9kLmN1LmVkdTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALr4h7ZXlqlndNCqMSrOacBvquboeHePjAcmbMvPXEjKOhqftQ+KqYGlzg0+37MvT5f779at+X3lsiGc2dvTHUESAhy/Tni+RXMekB/J9+KVVfz6PLfAHjWrBBgWYlVl7x6ck+NZEp+A2ySgkKJNoVDXx33lI0MphNliUmQ1BW2DFJlUkkq+wI3cwj7R/kllsjW+9Cdzos9/XSByd/lZ//Um1sz362RU2UJiWKjs9sn4x5gLBMfBAxt88NbTOdnfGaf42jiPjSvsva8iXP3s9THigsi+QWldHCyuWXItrKGL3C/bVNsyaA5AW/AaxSsGNMiI2QYXaUXW87cMiGL9YUkCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAHgPSkafiZN+6Hl07qMa+NtYH6qPbfukGtolAs+cwdI659ibZcYb721rSinJjEYYM+mxXDba8v6S3cC9HAJwZpGX5KZhgvfCaOHfIU7PJhmrJRnZkFWfIc4j2ej68cRUBgexceuoSeJboBkqaWUCMIyxFokSyR7RvkOVhd6y59sJTERDwwvAqwZk3b3EijspZJIkE8D1kPAK9+tmZTsReR7dTTz+gGx07WA6xCI0mel97OT7Ggi9TIqKSLVpFvBBYU8PaZnk+Ia8yIF3DuhUWZrzZIc667907kXoL2bJHOPei24RH+8lcNrbydL51I4UzYikHZI0Ard3a57fqiPXJTw==',
            ],
        ],
    ];
}
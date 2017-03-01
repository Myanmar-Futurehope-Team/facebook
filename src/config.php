<?php

return [

    /**
     * Graph API version used to request the Facebook API.
     */
    'apiVersion' => 'v2.8',

    /**
     * Whether request to APIs should be cached or not.
     */
    'enableCache' => true,

    /**
     * The amount of time cache should last.
     *
     * @see http://www.php.net/manual/en/dateinterval.construct.php
     */
    'cacheDuration' => 'PT1H',

    /**
     * OAuth client ID.
     */
    'oauthClientId' => null,

    /**
     * OAuth client secret.
     */
    'oauthClientSecret' => null,

    /**
     * OAuth scope.
     */
    'oauthScope' => ['public_profile', 'manage_pages', 'read_insights'],

    /**
     * OAuth provider authorization options.
     */
    'oauthAuthorizationOptions' => [],
];

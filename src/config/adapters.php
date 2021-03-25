<?php

return [
    /**
     * Default middleware, if middleware_groups are not specified
     */
    'default_middleware_groups' => null,

    /**
     * Middleware groups can be:
     * - null
     * - string (web, api, custom_group_name)
     * - array (list of groups; ex. ['web', 'api', 'foo', 'bar'])
     *
     * If no group has been specified, then getting default_middleware_group
     */
    'middleware_groups' => ['web'],

    'default_environment' => 'local',
    'use_environmental_proxy' => env('ADAPTERS_ENV_ENABLED', false),
    'environment' => mb_strtolower(env('ADAPTERS_ENV', env('APP_ENV', 'local'))),

    /**
     * Proxy does contain settings for the server, to which requests should be proxified
     */
    'proxy' => [
        /**
         * Schema can be:
         *
         * - http
         * - https (Default)
         */
        'schema' => env('ADAPTERS_DEFAULT_SCHEMA', 'https'),
        'uri' => env('ADAPTERS_DEFAULT_URI', 'httpbin.org'),
    ],

    'client' => [
        'headers' => [],
        'verify' => false,
    ],

    'environments' => [
        'local' => [
            'proxy' => [
                'schema' => 'https',
                'uri' => 'httpbin.org',
            ],
            'client' => [
                'verify' => false,
            ]
        ]
    ]
];

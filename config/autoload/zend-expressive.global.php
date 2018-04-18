<?php

declare(strict_types=1);

use Zend\ConfigAggregator\ConfigAggregator;

return [
    // Toggle the configuration cache. Set this to boolean false, or remove the
    // directive, to disable configuration caching. Toggling development mode
    // will also disable it by default; clear the configuration cache using
    // `composer clear-config-cache`.
    ConfigAggregator::ENABLE_CACHE => true,

    // Enable debugging; typically used to provide debugging information within templates.
    'debug' => false,

    'zend-expressive' => [
        // Enable programmatic pipeline: Any `middleware_pipeline` or `routes`
        // configuration will be ignored when creating the `Application` instance.
        'programmatic_pipeline' => true,

        // Provide templates for the error handling middleware to use when
        // generating responses.
        'error_handler' => [
            'template_404'   => 'error::404',
            'template_error' => 'error::error',
        ],
    ],

    'authorization' => [
        'roles' => [
            'default'  => [],
            'librarian' => ['default'],
            'admin'  => ['librarian'], //can view users from all pura libraries
            'Z01'  => ['librarian'],
            'A100'  => ['librarian'],
        ],
        'resources' => [
            'home',
            'user.logout',
            'user.login',
            'purauser.barcodeentry',
            'purauser.alephnrentry',
            'purauser.edit',
            'purauser.edit.emptyuser',
            'purauser.search',
            'purauser.block',
        ],
        'allow' => [
            'default' => [
                'home',
                'user.logout',
                'user.login',
            ],
            'librarian' => [
                'purauser.barcodeentry',
                'purauser.alephnrentry',
                'purauser.edit',
                'purauser.edit.emptyuser',
                'purauser.search',
                'purauser.block',
            ],
        ],
    ],
];

<?php declare(strict_types=1);

namespace LogSentry;

return [
    'logger' => [
        'writers' => [
            'sentry' => true,
        ],

        // The logger uses the Laminas Log configuration.
        // @see https://docs.laminas.dev/laminas-log
        // @see https://docs.laminas.dev/laminas-log/service-manager
        'options' => [
            'writers' => [
                // See https://github.com/facile-it/sentry-module#log-writer
                'sentry' => [
                    'name' => \Facile\SentryModule\Log\Writer\Sentry::class,
                    'options' => [
                        'filters' => [
                            [
                                'name' => 'priority',
                                'options' => [
                                    // Sentry is an error monitoring service and the aim is to deploy
                                    // it to track end users errors.
                                    // So it is useless to track events that are not at least error or
                                    // eventually warning.
                                    // Note that the free Sentry subscription plan is limited to 5000 errors or
                                    // exceptions by month. So for development, you may use other loggers.
                                    'priority' => \Laminas\Log\Logger::ERR,
                                ],
                            ],
                        ],
                        // Handle all errors, not only exceptions. This is a specific option of this module,
                        'attach_to_logger' => false,
                    ],
                ],
            ],
        ],
    ],
    /**
     * Set specific config for Sentry.
     * Don't update values here, but copy the needed keys at the root of Omeka in config/local.config.php.
     * The only required value is:
     * - dsn, that is a url provided by Sentry used to authenticate and log.
     * @see https://github.com/facile-it/sentry-module#client
     * @see https://docs.sentry.io/platforms/php/configuration/
     */
    'sentry' => [
        'disable_module' => false,
        'options' => [
            // Sentry dsn.
            'dsn' => '',
            // other sentry options
            // https://docs.sentry.io/error-reporting/configuration/?platform=php
        ],
        'javascript' => [
            'inject_script' => false,
            'options' => [
                // Sentry Raven dsn.
                'dsn' => '',
                // other sentry options
                // https://docs.sentry.io/platforms/javascript
            ],
        ],
    ],
];

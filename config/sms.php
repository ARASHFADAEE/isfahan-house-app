<?php

return [
    // Master toggle for SMS sending
    'enabled' => env('SMS_ENABLED', true),

    // Default provider name (must exist in `providers` map below)
    'default_provider' => env('SMS_PROVIDER', 'melli'),

    // Default sender line (fallback for providers that accept sender):
    'from' => env('SMS_SENDER', null),

    // Provider registry and configuration
    'providers' => [
        'melli' => [
            'class' => \App\Modules\Sms\Providers\MelliSmsProvider::class,
            'wsdl' => env('SMS_MELLI_WSDL', 'http://api.payamak-panel.com/post/Send.asmx?wsdl'),
            'username' => env('SMS_MELLI_USERNAME'),
            'password' => env('SMS_MELLI_PASSWORD'),
            'from' => env('SMS_MELLI_FROM', env('SMS_SENDER')),
            // Map of logical template names to MelliPayamak body IDs
            'template_body_ids' => [
                'verify_code' => env('SMS_TEMPLATE_VERIFY_CODE'),
            ],
        ],
    ],
];
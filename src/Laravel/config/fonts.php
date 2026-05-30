<?php
    return [
        'stack' => ['google-v2', 'fontawesome'],

        'google' => [
            'driver' => 'google-v2',
            'fonts' => [
                'Oswald' => ['300', '400'],
                'Open Sans' => ['300..800']
            ]
        ],

        'bunny' => [
            'driver' => 'bunny',
            'fonts' => [
                'Oswald' => ['300', '400'],
                'Open Sans' => ['300..800']
            ]
        ],

        'adobe' => [
            'driver' => 'adobe',
            'fonts' => [
                env('ADOBE_KIT_ID', 'your_kit_id'),
            ]
        ],

        'fontawesome' => [
            'driver' => 'fontawesome',
            'fonts' => [
                env('FONTAWESOME_KIT_ID', 'your_kit_id'),
            ]
        ],
    ];
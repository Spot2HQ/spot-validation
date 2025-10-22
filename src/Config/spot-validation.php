<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Spot Validation Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the Spot Validation
    | package. You can customize validation behavior, limits, and settings.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Validation Settings
    |--------------------------------------------------------------------------
    |
    | General validation settings for spot data.
    |
    */

    'validation' => [
        'description_max_length' => 450,
        'pdf_description_max_length' => 450,
        'max_square_space' => 99999999,
        'max_price' => 10000000000,
        'max_front_meters' => 1000,
        'max_height_meters' => 1000,
        'max_parking_spaces' => 9999,
        'max_charging_ports' => 1000,
        'max_vehicle_ramps' => 99,
        'max_elevators' => 999,
    ],

    /*
    |--------------------------------------------------------------------------
    | Pricing Configuration
    |--------------------------------------------------------------------------
    |
    | Default values and settings for price calculations and exchange rates.
    | Projects can override the exchange rate provider by binding their own
    | implementation to ExchangeRateProviderInterface in their service provider.
    |
    */

    'pricing' => [
        'default_exchange_rate' => env('DEFAULT_EXCHANGE_RATE', 20.00), // USD to MXN
        'decimal_places' => 2, // Number of decimal places for prices
        'minimum_price_per_area' => 10000, // Minimum price per area in MXN
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    |
    | Enable or disable specific features in the validation package.
    |
    */

    'features' => [
        'strict_validation' => env('SPOT_VALIDATION_STRICT', false),
        'validate_external_ids' => true,
        'require_photos' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Coordinate Limits
    |--------------------------------------------------------------------------
    |
    | Latitude and longitude validation ranges.
    |
    */

    'coordinates' => [
        'latitude' => [
            'min' => -90,
            'max' => 90,
        ],
        'longitude' => [
            'min' => -180,
            'max' => 180,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Messages
    |--------------------------------------------------------------------------
    |
    | You can define custom validation messages here. Leave empty to use
    | Laravel's default messages.
    |
    */

    'messages' => [
        // 'spot_type_id.required' => 'El tipo de inmueble es requerido.',
        // 'square_space.required' => 'El área del inmueble es requerida.',
    ],
];


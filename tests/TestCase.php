<?php

namespace Spot2HQ\SpotValidation\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Spot2HQ\SpotValidation\Providers\ValidationServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Register service providers 
     * 
     */
    protected function getPackageProviders($app): array
    {
        return [
            ValidationServiceProvider::class,
        ];
    }

    /**
     * Configurar ambiente de testing
     */
    protected function getEnvironmentSetUp($app): void
    {
        // Set up package configuration for testing
        $app['config']->set('spot-validation', [
            'validation' => [
                'description_max_length' => 450,
                'max_price' => 10000000000,
                'max_square_space' => 99999999,
            ],
            'pricing' => [
                'default_exchange_rate' => 20.00,
                'minimum_price_per_area' => 10000,
                'decimal_places' => 2,
            ],
            'features' => [
                'strict_validation' => false,
                'validate_external_ids' => true,
                'require_photos' => false,
            ],
            'coordinates' => [
                'latitude' => ['min' => -90, 'max' => 90],
                'longitude' => ['min' => -180, 'max' => 180],
            ],
        ]);

        // Set up photos configuration for testing
        $app['config']->set('photos', [
            'types' => [
                1 => 'Normal',
                2 => 'Mapa',
                3 => 'Fachada',
                4 => 'Interior',
                5 => 'Exterior',
                6 => 'Detalles',
            ],
            'google_drive_pattern' => "/drive\.google\.com\/file\/d\/.+\/view/",
            'file_id_pattern' => "/([a-z\d_-]{25,})[$\/&?]/i",
            'valid_image_types' => ['image', 'image/png', 'image/jpeg', 'image/webp'],
        ]);

        // Set up prices configuration for testing
        $app['config']->set('prices', [
            'USD_CURRENCY_TYPE' => 2,
            'AREA_TYPE_M2' => 2,
        ]);
    }

}
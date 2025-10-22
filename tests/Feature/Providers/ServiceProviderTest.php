<?php

namespace Spot2HQ\SpotValidation\Tests\Feature\Providers;

use Spot2HQ\SpotValidation\Tests\TestCase;
use Spot2HQ\SpotValidation\Providers\ValidationServiceProvider;
use Spot2HQ\SpotValidation\Contracts\ExchangeRateProviderInterface;
use Spot2HQ\SpotValidation\Providers\DefaultExchangeRateProvider;
use Illuminate\Support\Facades\Config;

/**
 * Test Service Provider Registration
 * 
 * @group feature
 * @group service-provider
 */
class ServiceProviderTest extends TestCase
{
    public function test_service_provider_exists(): void
    {
        // Test that the service provider exists
        $this->assertTrue(class_exists(ValidationServiceProvider::class));
    }

    public function test_service_provider_extends_laravel_service_provider(): void
    {
        // Test that ValidationServiceProvider extends Laravel's ServiceProvider
        $provider = new ValidationServiceProvider($this->app);
        $this->assertInstanceOf(\Illuminate\Support\ServiceProvider::class, $provider);
    }

    public function test_service_provider_registers_configuration(): void
    {
        // Test that configuration is published
        $configPath = config_path('spot-validation.php');
        
        // In a package context, we can't test file publishing, but we can test config access
        $this->assertIsArray(config('spot-validation'));
        $this->assertArrayHasKey('pricing', config('spot-validation'));
    }

    public function test_service_provider_binds_exchange_rate_provider(): void
    {
        // Test that the exchange rate provider interface is bound
        $provider = app(ExchangeRateProviderInterface::class);
        $this->assertInstanceOf(DefaultExchangeRateProvider::class, $provider);
    }

    public function test_service_provider_configuration_structure(): void
    {
        // Test that the configuration has the expected structure
        $config = config('spot-validation');
        
        $this->assertArrayHasKey('pricing', $config);
        $this->assertArrayHasKey('default_exchange_rate', $config['pricing']);
        $this->assertArrayHasKey('decimal_places', $config['pricing']);
        $this->assertArrayHasKey('minimum_price_per_area', $config['pricing']);
    }

    public function test_service_provider_configuration_values(): void
    {
        // Test that configuration has reasonable default values
        $config = config('spot-validation');
        
        $this->assertIsFloat($config['pricing']['default_exchange_rate']);
        $this->assertGreaterThan(0, $config['pricing']['default_exchange_rate']);
        
        $this->assertIsInt($config['pricing']['decimal_places']);
        $this->assertGreaterThanOrEqual(0, $config['pricing']['decimal_places']);
        
        $this->assertIsInt($config['pricing']['minimum_price_per_area']);
        $this->assertGreaterThan(0, $config['pricing']['minimum_price_per_area']);
    }

    public function test_service_provider_environment_configuration(): void
    {
        // Test that environment variables can override configuration
        Config::set('spot-validation.pricing.default_exchange_rate', 25.50);
        
        $this->assertEquals(25.50, config('spot-validation.pricing.default_exchange_rate'));
        
        // Test that the bound provider uses the updated configuration
        $provider = app(ExchangeRateProviderInterface::class);
        $this->assertEquals(25.50, $provider->getUsdToMxnRate());
    }

    public function test_service_provider_registers_validation_rules(): void
    {
        // Test that custom validation rules are available
        // This is more of an integration test to ensure the service provider
        // doesn't break when registering validation rules
        
        $this->assertTrue(true); // Placeholder - validation rule registration is tested in unit tests
    }

    public function test_service_provider_boot_method(): void
    {
        // Test that the service provider can be booted without errors
        $provider = new ValidationServiceProvider($this->app);
        
        // This should not throw any exceptions
        $provider->boot();
        $this->assertTrue(true);
    }

    public function test_service_provider_register_method(): void
    {
        // Test that the service provider can be registered without errors
        $provider = new ValidationServiceProvider($this->app);
        
        // This should not throw any exceptions
        $provider->register();
        $this->assertTrue(true);
    }

    public function test_service_provider_provides_method(): void
    {
        // Test that the service provider declares what it provides
        $provider = new ValidationServiceProvider($this->app);
        
        $provides = $provider->provides();
        
        $this->assertIsArray($provides);
        $this->assertContains('spot-validation', $provides);
    }

    public function test_service_provider_deferred_providers(): void
    {
        // Test that the service provider is properly deferred
        $provider = new ValidationServiceProvider($this->app);
        
        // The ValidationServiceProvider doesn't implement isDeferred, so it's not deferred
        $this->assertFalse(method_exists($provider, 'isDeferred') ? $provider->isDeferred() : false);
    }
}
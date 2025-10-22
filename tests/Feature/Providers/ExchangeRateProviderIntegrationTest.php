<?php

namespace Spot2HQ\SpotValidation\Tests\Feature\Providers;

use Spot2HQ\SpotValidation\Tests\TestCase;
use Spot2HQ\SpotValidation\Contracts\ExchangeRateProviderInterface;
use Spot2HQ\SpotValidation\Providers\DefaultExchangeRateProvider;
use Illuminate\Support\Facades\Config;

/**
 * Test Exchange Rate Provider Integration
 * 
 * @group feature
 * @group providers
 * @group exchange-rate
 */
class ExchangeRateProviderIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up configuration
        Config::set('spot-validation.pricing.default_exchange_rate', 20.00);
    }

    public function test_exchange_rate_provider_interface_exists(): void
    {
        // Test that the interface exists
        $this->assertTrue(interface_exists(ExchangeRateProviderInterface::class));
    }

    public function test_default_exchange_rate_provider_exists(): void
    {
        // Test that the default provider exists
        $this->assertTrue(class_exists(DefaultExchangeRateProvider::class));
    }

    public function test_default_provider_implements_interface(): void
    {
        // Test that DefaultExchangeRateProvider implements the interface
        $provider = new DefaultExchangeRateProvider();
        $this->assertInstanceOf(ExchangeRateProviderInterface::class, $provider);
    }

    public function test_provider_is_bound_in_service_container(): void
    {
        // Test that the interface is bound to the default provider
        $provider = app(ExchangeRateProviderInterface::class);
        $this->assertInstanceOf(DefaultExchangeRateProvider::class, $provider);
    }

    public function test_provider_uses_configuration(): void
    {
        // Test that the provider uses configuration values
        Config::set('spot-validation.pricing.default_exchange_rate', 25.50);
        
        $provider = new DefaultExchangeRateProvider();
        $this->assertEquals(25.50, $provider->getUsdToMxnRate());
    }

    public function test_provider_conversion_methods(): void
    {
        $provider = new DefaultExchangeRateProvider();
        
        // Test USD to MXN conversion
        $mxnAmount = $provider->convertUsdToMxn(100.00);
        $this->assertEquals(2000.00, $mxnAmount);
        
        // Test MXN to USD conversion
        $usdAmount = $provider->convertMxnToUsd(2000.00);
        $this->assertEquals(100.00, $usdAmount);
    }

    public function test_custom_provider_can_be_bound(): void
    {
        // Create a custom provider
        $customProvider = $this->createMock(ExchangeRateProviderInterface::class);
        $customProvider->method('getUsdToMxnRate')->willReturn(25.00);
        $customProvider->method('convertUsdToMxn')->willReturnCallback(fn($amount) => $amount * 25.00);
        $customProvider->method('convertMxnToUsd')->willReturnCallback(fn($amount) => $amount / 25.00);
        
        // Bind custom provider
        $this->app->bind(ExchangeRateProviderInterface::class, function () use ($customProvider) {
            return $customProvider;
        });
        
        // Test that custom provider is used
        $provider = app(ExchangeRateProviderInterface::class);
        $this->assertEquals(25.00, $provider->getUsdToMxnRate());
        $this->assertEquals(2500.00, $provider->convertUsdToMxn(100.00));
    }

    public function test_provider_handles_zero_exchange_rate(): void
    {
        // Test edge case with zero exchange rate
        Config::set('spot-validation.pricing.default_exchange_rate', 0.0);
        
        $provider = new DefaultExchangeRateProvider();
        
        // Should handle division by zero gracefully
        $usdAmount = $provider->convertMxnToUsd(1000.00);
        $this->assertEquals(1000.00, $usdAmount); // Should return original amount
    }

    public function test_provider_with_different_configurations(): void
    {
        // Test with different exchange rates
        $testRates = [15.00, 20.00, 25.50, 30.75];
        
        foreach ($testRates as $rate) {
            Config::set('spot-validation.pricing.default_exchange_rate', $rate);
            
            $provider = new DefaultExchangeRateProvider();
            $this->assertEquals($rate, $provider->getUsdToMxnRate());
            
            // Test conversion
            $testAmount = 100.00;
            $expectedMxn = $testAmount * $rate;
            $this->assertEquals($expectedMxn, $provider->convertUsdToMxn($testAmount));
            
            // Test reverse conversion
            $expectedUsd = $expectedMxn / $rate;
            $this->assertEquals($expectedUsd, $provider->convertMxnToUsd($expectedMxn));
        }
    }

    public function test_provider_integration_with_validation(): void
    {
        // Test that the provider works with validation rules
        $provider = app(ExchangeRateProviderInterface::class);
        
        // This should not throw any exceptions
        $rate = $provider->getUsdToMxnRate();
        $this->assertIsFloat($rate);
        $this->assertGreaterThan(0, $rate);
        
        // Test conversions don't throw exceptions
        $mxnAmount = $provider->convertUsdToMxn(100.00);
        $usdAmount = $provider->convertMxnToUsd($mxnAmount);
        
        $this->assertIsFloat($mxnAmount);
        $this->assertIsFloat($usdAmount);
        $this->assertEquals(100.00, $usdAmount, '', 0.01); // Allow small floating point differences
    }
}
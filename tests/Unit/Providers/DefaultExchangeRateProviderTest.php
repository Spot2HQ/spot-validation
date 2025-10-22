<?php

namespace Spot2HQ\SpotValidation\Tests\Unit\Providers;

use Spot2HQ\SpotValidation\Tests\TestCase;
use Spot2HQ\SpotValidation\Providers\DefaultExchangeRateProvider;
use Illuminate\Support\Facades\Config;

/**
 * Test DefaultExchangeRateProvider
 * 
 * @group unit
 * @group providers
 * @group exchange-rate
 */
class DefaultExchangeRateProviderTest extends TestCase
{
    private DefaultExchangeRateProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up default configuration
        Config::set('spot-validation.pricing.default_exchange_rate', 20.00);
        
        $this->provider = new DefaultExchangeRateProvider();
    }

    public function test_it_returns_correct_exchange_rate(): void
    {
        $rate = $this->provider->getUsdToMxnRate();
        
        $this->assertEquals(20.00, $rate);
    }

    public function test_it_converts_usd_to_mxn_correctly(): void
    {
        $mxnAmount = $this->provider->convertUsdToMxn(100.00);
        
        $this->assertEquals(2000.00, $mxnAmount);
    }

    public function test_it_converts_mxn_to_usd_correctly(): void
    {
        $usdAmount = $this->provider->convertMxnToUsd(2000.00);
        
        $this->assertEquals(100.00, $usdAmount);
    }

    public function test_it_handles_zero_amounts(): void
    {
        $this->assertEquals(0.0, $this->provider->convertUsdToMxn(0));
        $this->assertEquals(0.0, $this->provider->convertMxnToUsd(0));
    }

    public function test_it_handles_decimal_amounts(): void
    {
        $mxnAmount = $this->provider->convertUsdToMxn(1.50);
        $this->assertEquals(30.00, $mxnAmount);
        
        $usdAmount = $this->provider->convertMxnToUsd(30.00);
        $this->assertEquals(1.50, $usdAmount);
    }

    public function test_it_uses_custom_exchange_rate_from_config(): void
    {
        Config::set('spot-validation.pricing.default_exchange_rate', 25.50);
        
        $provider = new DefaultExchangeRateProvider();
        
        $this->assertEquals(25.50, $provider->getUsdToMxnRate());
        $this->assertEquals(255.00, $provider->convertUsdToMxn(10.00));
        $this->assertEquals(10.00, $provider->convertMxnToUsd(255.00));
    }
}
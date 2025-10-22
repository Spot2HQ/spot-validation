<?php

namespace Spot2HQ\SpotValidation\Tests\Unit\Rules;

use Spot2HQ\SpotValidation\Tests\TestCase;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\MinPriceByArea;
use Spot2HQ\SpotValidation\Providers\DefaultExchangeRateProvider;

/**
 * Test MinPriceByArea validation rule
 * 
 * @group unit
 * @group validation
 * @group price
 */
class MinPriceByAreaTest extends TestCase
{
    private DefaultExchangeRateProvider $exchangeRateProvider;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->exchangeRateProvider = new DefaultExchangeRateProvider();
    }

    public function test_it_accepts_valid_mxn_price_for_total_area(): void
    {
        $rule = new MinPriceByArea(
            square_space: 1000,
            price_area: 1, // Total area
            currency_type: 1, // MXN
            label: 'renta',
            exchangeRateProvider: $this->exchangeRateProvider
        );

        $failCalled = false;
        $rule->validate('rent_price', 15000, function ($message) use (&$failCalled) {
            $failCalled = true;
        });

        $this->assertFalse($failCalled, 'Validation should pass for valid MXN price');
    }

    public function test_it_rejects_low_mxn_price_for_total_area(): void
    {
        $rule = new MinPriceByArea(
            square_space: 1000,
            price_area: 1, // Total area
            currency_type: 1, // MXN
            label: 'renta',
            exchangeRateProvider: $this->exchangeRateProvider
        );

        $failCalled = false;
        $failMessage = '';
        $rule->validate('rent_price', 5000, function ($message) use (&$failCalled, &$failMessage) {
            $failCalled = true;
            $failMessage = $message;
        });

        $this->assertTrue($failCalled, 'Validation should fail for low MXN price');
        $this->assertStringContainsString('below the minimum price per area', $failMessage);
    }

    public function test_it_accepts_valid_usd_price_for_total_area(): void
    {
        $rule = new MinPriceByArea(
            square_space: 1000,
            price_area: 1, // Total area
            currency_type: 2, // USD
            label: 'renta',
            exchangeRateProvider: $this->exchangeRateProvider
        );

        $failCalled = false;
        $rule->validate('rent_price', 600, function ($message) use (&$failCalled) {
            $failCalled = true;
        });

        $this->assertFalse($failCalled, 'Validation should pass for valid USD price (600 * 20 = 12000 MXN)');
    }

    public function test_it_rejects_low_usd_price_for_total_area(): void
    {
        $rule = new MinPriceByArea(
            square_space: 1000,
            price_area: 1, // Total area
            currency_type: 2, // USD
            label: 'renta',
            exchangeRateProvider: $this->exchangeRateProvider
        );

        $failCalled = false;
        $rule->validate('rent_price', 400, function ($message) use (&$failCalled) {
            $failCalled = true;
        });

        $this->assertTrue($failCalled, 'Validation should fail for low USD price (400 * 20 = 8000 MXN)');
    }

    public function test_it_generates_correct_error_message_for_mxn(): void
    {
        $rule = new MinPriceByArea(
            square_space: 1000,
            price_area: 1,
            currency_type: 1, // MXN
            label: 'renta',
            exchangeRateProvider: $this->exchangeRateProvider
        );

        $message = $rule->message();
        
        $this->assertStringContainsString('renta', $message);
        $this->assertStringContainsString('MXN', $message);
        $this->assertStringContainsString('10,000.00', $message);
    }

    public function test_it_generates_correct_error_message_for_usd(): void
    {
        $rule = new MinPriceByArea(
            square_space: 1000,
            price_area: 1,
            currency_type: 2, // USD
            label: 'venta',
            exchangeRateProvider: $this->exchangeRateProvider
        );

        $message = $rule->message();
        
        $this->assertStringContainsString('venta', $message);
        $this->assertStringContainsString('USD', $message);
        $this->assertStringContainsString('500.00', $message); // 10000 / 20 = 500
    }
}
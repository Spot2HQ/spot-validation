<?php

namespace Spot2HQ\SpotValidation\Tests\Feature\Requests\Spots;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Spot2HQ\SpotValidation\Contracts\ExchangeRateProviderInterface;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceAreaTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceCurrencyTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceTypeEnum;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\SharedRules;
use Spot2HQ\SpotValidation\Tests\Helpers\ValidationRuleExtractor;
use Spot2HQ\SpotValidation\Tests\TestCase;

/**
 * Test MinPriceByArea validation in SharedRules context
 * 
 * @group feature
 * @group validation
 * @group price
 * @group shared-rules
 */
class MinPriceByAreaSharedRulesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up configuration
        Config::set('spot-validation.pricing.minimum_price_per_area', 10000);
        Config::set('spot-validation.pricing.default_exchange_rate', 20.00);
    }

    protected function getBaseSpotData(): array
    {
        return [
            'name' => 'Test Spot',
            'square_space' => 1000,
            'modality_type' => PriceTypeEnum::RENT->value,
            'currency_type' => PriceCurrencyTypeEnum::MXN->value,
            'rent_price_area' => PriceAreaTypeEnum::TOTAL->value,
        ];
    }

    protected function getBaseSpotDataWithUsd(): array
    {
        return [
            'name' => 'Test Spot',
            'square_space' => 1000,
            'modality_type' => PriceTypeEnum::RENT->value,
            'currency_type' => PriceCurrencyTypeEnum::USD->value,
            'rent_price_area' => PriceAreaTypeEnum::TOTAL->value,
        ];
    }

    public function test_shared_rules_trait_exists(): void
    {
        // Simple test to verify the SharedRules trait exists and can be used
        $this->assertTrue(trait_exists(SharedRules::class));
    }

    public function test_price_rules_method_exists(): void
    {
        // Extract actual rules from the trait
        $rules = ValidationRuleExtractor::getPriceRules();
        
        $this->assertIsArray($rules);
        $this->assertArrayHasKey('rent_price', $rules);
        $this->assertArrayHasKey('sale_price', $rules);
        $this->assertArrayHasKey('modality_type', $rules);
        $this->assertArrayHasKey('currency_type', $rules);
    }

    public function test_it_validates_mxn_rent_price_for_total_area(): void
    {
        $data = $this->getBaseSpotData();
        $data['rent_price'] = 15000; // Above minimum of 10000

        // Extract actual rules from the trait and simplify for package testing
        $allRules = ValidationRuleExtractor::getPriceRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes(), 
            'Should pass with valid MXN rent price. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_fails_mxn_rent_price_below_minimum(): void
    {
        $data = $this->getBaseSpotData();
        $data['rent_price'] = 5000; // Below minimum of 10000

        // Extract actual rules from the trait and simplify for package testing
        $allRules = ValidationRuleExtractor::getPriceRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);

        $validator = Validator::make($data, $rules);

        // The important thing is that we're testing with the actual rules from the trait
        $this->assertArrayHasKey('rent_price', $validator->errors()->toArray());
    }

    public function test_it_validates_usd_rent_price_for_total_area(): void
    {
        $data = $this->getBaseSpotDataWithUsd();
        $data['rent_price'] = 600; // 600 * 20 = 12000 MXN (above minimum)

        // Extract actual rules from the trait with USD currency context
        $allRules = ValidationRuleExtractor::getPriceRulesWithUsd();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes(), 
            'Should pass with valid USD rent price. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_fails_usd_rent_price_below_minimum(): void
    {
        $data = $this->getBaseSpotDataWithUsd();
        $data['rent_price'] = 400; // 400 * 20 = 8000 MXN (below minimum)

        // Extract actual rules from the trait with USD currency context
        $allRules = ValidationRuleExtractor::getPriceRulesWithUsd();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('rent_price', $validator->errors()->toArray());
    }

    public function test_it_uses_custom_exchange_rate_provider(): void
    {
        // Create custom provider with different exchange rate
        $customProvider = $this->createMock(ExchangeRateProviderInterface::class);
        $customProvider->method('getUsdToMxnRate')->willReturn(25.00);
        $customProvider->method('convertUsdToMxn')->willReturnCallback(fn($amount) => $amount * 25.00);
        $customProvider->method('convertMxnToUsd')->willReturnCallback(fn($amount) => $amount / 25.00);

        // Bind custom provider
        $this->app->bind(ExchangeRateProviderInterface::class, function () use ($customProvider) {
            return $customProvider;
        });

        $data = $this->getBaseSpotDataWithUsd();
        $data['rent_price'] = 500; // 500 * 25 = 12500 MXN (above minimum with custom rate)

        // Extract actual rules from the trait with USD currency context
        $allRules = ValidationRuleExtractor::getPriceRulesWithUsd();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes(), 
            'Should pass with custom exchange rate provider. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_price_modality_type(): void
    {
        $data = $this->getBaseSpotData();
        
        // Test different modality types
        $modalityTypes = [
            PriceTypeEnum::RENT->value,
            PriceTypeEnum::SALE->value,
            PriceTypeEnum::RENT_AND_SALE->value,
        ];
        
        foreach ($modalityTypes as $modalityType) {
            $data['modality_type'] = $modalityType;
            
            // Add required price fields based on modality type
            if ($modalityType === PriceTypeEnum::RENT->value || $modalityType === PriceTypeEnum::RENT_AND_SALE->value) {
                $data['rent_price'] = 15000; // Valid rent price
                $data['rent_price_area'] = PriceAreaTypeEnum::TOTAL->value; // Required when rent_price is present
            }
            if ($modalityType === PriceTypeEnum::SALE->value || $modalityType === PriceTypeEnum::RENT_AND_SALE->value) {
                $data['sale_price'] = 200000; // Valid sale price
                $data['sale_price_area'] = PriceAreaTypeEnum::TOTAL->value; // Required when sale_price is present
            }
            
            // Extract actual rules from the trait
            $allRules = ValidationRuleExtractor::getPriceRules();
            $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
            
            $validator = Validator::make($data, $rules);
            
            $this->assertTrue($validator->passes(), 
                "Should pass with modality type {$modalityType}. Errors: " . json_encode($validator->errors()->toArray())
            );
        }
    }

    public function test_it_validates_currency_type(): void
    {
        $data = $this->getBaseSpotData();
        
        // Test different currency types
        $currencyTypes = [
            PriceCurrencyTypeEnum::MXN->value,
            PriceCurrencyTypeEnum::USD->value,
        ];
        
        foreach ($currencyTypes as $currencyType) {
            $data['currency_type'] = $currencyType;
            $data['rent_price'] = 15000; // Add required rent price
            
            // Extract actual rules from the trait
            $allRules = ValidationRuleExtractor::getPriceRules();
            $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
            
            $validator = Validator::make($data, $rules);
            
            $this->assertTrue($validator->passes(), 
                "Should pass with currency type {$currencyType}. Errors: " . json_encode($validator->errors()->toArray())
            );
        }
    }

    public function test_it_fails_with_invalid_modality_type(): void
    {
        $data = $this->getBaseSpotData();
        $data['modality_type'] = 999; // Invalid modality type
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getPriceRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with invalid modality type'
        );
        $this->assertArrayHasKey('modality_type', $validator->errors()->toArray());
    }

    public function test_it_fails_with_invalid_currency_type(): void
    {
        $data = $this->getBaseSpotData();
        $data['currency_type'] = 999; // Invalid currency type
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getPriceRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with invalid currency type'
        );
        $this->assertArrayHasKey('currency_type', $validator->errors()->toArray());
    }
}
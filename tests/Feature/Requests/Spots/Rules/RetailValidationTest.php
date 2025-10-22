<?php

namespace Spot2HQ\SpotValidation\Tests\Feature\Requests\Spots\Rules;

use Illuminate\Support\Facades\Validator;
use Spot2HQ\SpotValidation\Enums\Spot\SpotTypeEnum;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\RetailRules;
use Spot2HQ\SpotValidation\Tests\Helpers\ValidationRuleExtractor;
use Spot2HQ\SpotValidation\Tests\TestCase;

/**
 * Test Retail-specific validation rules
 * 
 * @group feature
 * @group validation
 * @group retail
 */
class RetailValidationTest extends TestCase
{
    protected function getBaseRetailData(): array
    {
        return [
            'name' => 'Retail Store',
            'spot_type_id' => SpotTypeEnum::RETAIL->value,
            'square_space' => 500.00,
            'is_complex' => 0,
        ];
    }

    public function test_retail_rules_trait_exists(): void
    {
        // Simple test to verify the RetailRules trait exists and can be used
        $this->assertTrue(trait_exists(RetailRules::class));
    }

    public function test_retail_rules_trait_can_be_used(): void
    {
        // Extract actual rules from the trait
        $rules = ValidationRuleExtractor::getRetailRules();
        
        $this->assertIsArray($rules);
        $this->assertArrayHasKey('glove', $rules);
    }

    public function test_it_validates_glove(): void
    {
        $data = $this->getBaseRetailData();
        $data['glove'] = 12.5;
        
        // Extract actual rules from the trait and simplify for package testing
        $allRules = ValidationRuleExtractor::getRetailRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid glove value. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_allows_null_glove(): void
    {
        $data = $this->getBaseRetailData();
        $data['glove'] = null;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getRetailRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with null glove value. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_allows_zero_glove(): void
    {
        $data = $this->getBaseRetailData();
        $data['glove'] = 0;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getRetailRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with zero glove value. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_fails_with_negative_glove(): void
    {
        $data = $this->getBaseRetailData();
        $data['glove'] = -3.5; // Below minimum of 0
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getRetailRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with negative glove value'
        );
        $this->assertArrayHasKey('glove', $validator->errors()->toArray());
    }

    public function test_it_validates_large_glove_values(): void
    {
        $data = $this->getBaseRetailData();
        $data['glove'] = 999.99; // Large but valid value
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getRetailRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with large glove value. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_decimal_glove_values(): void
    {
        $data = $this->getBaseRetailData();
        $data['glove'] = 15.75; // Decimal value
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getRetailRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with decimal glove value. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_retail_without_glove(): void
    {
        $data = $this->getBaseRetailData();
        // No glove field provided - should still pass
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getRetailRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass without glove field. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_string_glove_value(): void
    {
        $data = $this->getBaseRetailData();
        $data['glove'] = '10.5'; // String numeric value
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getRetailRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with string glove value. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_fails_with_non_numeric_glove(): void
    {
        $data = $this->getBaseRetailData();
        $data['glove'] = 'invalid'; // Non-numeric value
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getRetailRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with non-numeric glove value'
        );
        $this->assertArrayHasKey('glove', $validator->errors()->toArray());
    }
}

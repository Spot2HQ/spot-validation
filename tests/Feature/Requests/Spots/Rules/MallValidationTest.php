<?php

namespace Spot2HQ\SpotValidation\Tests\Feature\Requests\Spots\Rules;

use Illuminate\Support\Facades\Validator;
use Spot2HQ\SpotValidation\Enums\Spot\SpotTypeEnum;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\MallRules;
use Spot2HQ\SpotValidation\Tests\Helpers\ValidationRuleExtractor;
use Spot2HQ\SpotValidation\Tests\TestCase;

/**
 * Test Mall-specific validation rules
 * 
 * @group feature
 * @group validation
 * @group mall
 */
class MallValidationTest extends TestCase
{
    protected function getBaseMallData(): array
    {
        return [
            'name' => 'Shopping Mall',
            'spot_type_id' => SpotTypeEnum::SHOPPING_CENTER->value,
            'square_space' => 10000.00,
            'is_complex' => 1,
        ];
    }

    public function test_mall_rules_trait_exists(): void
    {
        // Simple test to verify the MallRules trait exists and can be used
        $this->assertTrue(trait_exists(MallRules::class));
    }

    public function test_mall_rules_trait_can_be_used(): void
    {
        // Extract actual rules from the trait
        $rules = ValidationRuleExtractor::getMallRules();
        
        $this->assertIsArray($rules);
        $this->assertArrayHasKey('certification', $rules);
        $this->assertArrayHasKey('glove', $rules);
    }

    public function test_it_validates_certification(): void
    {
        $data = $this->getBaseMallData();
        $data['certification'] = 'LEED Platinum';
        
        // Extract actual rules from the trait and simplify for package testing
        $allRules = ValidationRuleExtractor::getMallRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid certification. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_glove(): void
    {
        $data = $this->getBaseMallData();
        $data['glove'] = 15.5;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getMallRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid glove value. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_allows_null_certification(): void
    {
        $data = $this->getBaseMallData();
        $data['certification'] = null;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getMallRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with null certification. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_allows_null_glove(): void
    {
        $data = $this->getBaseMallData();
        $data['glove'] = null;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getMallRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with null glove value. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_allows_zero_glove(): void
    {
        $data = $this->getBaseMallData();
        $data['glove'] = 0;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getMallRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with zero glove value. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_fails_with_negative_glove(): void
    {
        $data = $this->getBaseMallData();
        $data['glove'] = -5; // Below minimum of 0
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getMallRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with negative glove value'
        );
        $this->assertArrayHasKey('glove', $validator->errors()->toArray());
    }

    public function test_it_validates_multiple_mall_fields(): void
    {
        $data = $this->getBaseMallData();
        $data['certification'] = 'BREEAM Outstanding';
        $data['glove'] = 25.75;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getMallRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with multiple valid mall fields. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_empty_certification_string(): void
    {
        $data = $this->getBaseMallData();
        $data['certification'] = '';
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getMallRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with empty certification string. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }
}

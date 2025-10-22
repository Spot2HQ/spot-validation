<?php

namespace Spot2HQ\SpotValidation\Tests\Feature\Requests\Spots\Rules;

use Illuminate\Support\Facades\Validator;
use Spot2HQ\SpotValidation\Enums\Spot\BuildingClassEnum;
use Spot2HQ\SpotValidation\Enums\Spot\SpotTypeEnum;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\CorporateRules;
use Spot2HQ\SpotValidation\Tests\Helpers\ValidationRuleExtractor;
use Spot2HQ\SpotValidation\Tests\TestCase;

/**
 * Test Corporate-specific validation rules
 * 
 * @group feature
 * @group validation
 * @group corporate
 */
class CorporateValidationTest extends TestCase
{
    protected function getBaseCorporateData(): array
    {
        return [
            'name' => 'Corporate Building',
            'spot_type_id' => SpotTypeEnum::CORPORATE->value,
            'square_space' => 2000.00,
            'is_complex' => 0,
        ];
    }

    public function test_corporate_rules_trait_exists(): void
    {
        // Simple test to verify the CorporateRules trait exists and can be used
        $this->assertTrue(trait_exists(CorporateRules::class));
    }

    public function test_corporate_rules_trait_can_be_used(): void
    {
        // Extract actual rules from the trait
        $rules = ValidationRuleExtractor::getCorporateRules();
        
        $this->assertIsArray($rules);
        $this->assertArrayHasKey('average_floor_size', $rules);
        $this->assertArrayHasKey('certification', $rules);
        $this->assertArrayHasKey('class', $rules);
    }

    public function test_it_validates_average_floor_size(): void
    {
        $data = $this->getBaseCorporateData();
        $data['average_floor_size'] = 500.5;
        
        // Extract actual rules from the trait and simplify for package testing
        $allRules = ValidationRuleExtractor::getCorporateRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid average floor size. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_certification(): void
    {
        $data = $this->getBaseCorporateData();
        $data['certification'] = 'LEED Gold';
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getCorporateRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid certification. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_building_class(): void
    {
        $data = $this->getBaseCorporateData();
        $data['class'] = BuildingClassEnum::A->value;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getCorporateRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid building class. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_allows_null_certification(): void
    {
        $data = $this->getBaseCorporateData();
        $data['certification'] = null;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getCorporateRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with null certification. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_allows_null_building_class(): void
    {
        $data = $this->getBaseCorporateData();
        $data['class'] = null;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getCorporateRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with null building class. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_fails_with_invalid_average_floor_size(): void
    {
        $data = $this->getBaseCorporateData();
        $data['average_floor_size'] = 0; // Below minimum of 1
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getCorporateRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with invalid average floor size'
        );
        $this->assertArrayHasKey('average_floor_size', $validator->errors()->toArray());
    }

    public function test_it_fails_with_invalid_building_class(): void
    {
        $data = $this->getBaseCorporateData();
        $data['class'] = 999; // Invalid building class (not in enum)
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getCorporateRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with invalid building class'
        );
        $this->assertArrayHasKey('class', $validator->errors()->toArray());
    }

    public function test_it_validates_multiple_corporate_fields(): void
    {
        $data = $this->getBaseCorporateData();
        $data['average_floor_size'] = 750.25;
        $data['certification'] = 'BREEAM Excellent';
        $data['class'] = BuildingClassEnum::B->value;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getCorporateRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with multiple valid corporate fields. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }
}

<?php

namespace Spot2HQ\SpotValidation\Tests\Feature\Requests\Spots\Rules;

use Illuminate\Support\Facades\Validator;
use Spot2HQ\SpotValidation\Enums\Spot\FireProtectionSystemEnum;
use Spot2HQ\SpotValidation\Enums\Spot\SecurityTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\SpotTypeEnum;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\OfficeRules;
use Spot2HQ\SpotValidation\Tests\Helpers\ValidationRuleExtractor;
use Spot2HQ\SpotValidation\Tests\TestCase;

/**
 * Test Office-specific validation rules
 * 
 * @group feature
 * @group validation
 * @group office
 */
class OfficeValidationTest extends TestCase
{
    protected function getBaseOfficeData(): array
    {
        return [
            'name' => 'Office Building',
            'spot_type_id' => SpotTypeEnum::OFFICE->value,
            'square_space' => 3000.00,
            'is_complex' => 0,
        ];
    }

    public function test_office_rules_trait_exists(): void
    {
        // Simple test to verify the OfficeRules trait exists and can be used
        $this->assertTrue(trait_exists(OfficeRules::class));
    }

    public function test_office_rules_trait_can_be_used(): void
    {
        // Extract actual rules from the trait
        $rules = ValidationRuleExtractor::getOfficeRules();
        
        $this->assertIsArray($rules);
        $this->assertArrayHasKey('fire_protection_system', $rules);
        $this->assertArrayHasKey('height_between_floors', $rules);
        $this->assertArrayHasKey('min_area_divisible', $rules);
        $this->assertArrayHasKey('number_of_elevators', $rules);
        $this->assertArrayHasKey('office_age', $rules);
        $this->assertArrayHasKey('security_type', $rules);
    }

    public function test_it_validates_fire_protection_system_array(): void
    {
        $data = $this->getBaseOfficeData();
        $data['fire_protection_system'] = [
            FireProtectionSystemEnum::SPRINKLERS->value,
            FireProtectionSystemEnum::HYDRANTS->value,
            FireProtectionSystemEnum::FIRE_EXTINGUISHERS->value
        ];
        
        // Extract actual rules from the trait and simplify for package testing
        $allRules = ValidationRuleExtractor::getOfficeRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid fire protection system. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_height_between_floors(): void
    {
        $data = $this->getBaseOfficeData();
        $data['height_between_floors'] = 3.5;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getOfficeRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid height between floors. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_min_area_divisible(): void
    {
        $data = $this->getBaseOfficeData();
        $data['min_area_divisible'] = 50.25;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getOfficeRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid min area divisible. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_number_of_elevators(): void
    {
        $data = $this->getBaseOfficeData();
        $data['number_of_elevators'] = 4;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getOfficeRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid number of elevators. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_office_age(): void
    {
        $data = $this->getBaseOfficeData();
        $data['office_age'] = 10;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getOfficeRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid office age. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_security_type_array(): void
    {
        $data = $this->getBaseOfficeData();
        $data['security_type'] = [
            SecurityTypeEnum::CCTV->value,
            SecurityTypeEnum::GUARD_BOOTH->value
        ];
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getOfficeRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid security type. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_allows_null_fire_protection_system(): void
    {
        $data = $this->getBaseOfficeData();
        $data['fire_protection_system'] = null;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getOfficeRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with null fire protection system. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_allows_null_security_type(): void
    {
        $data = $this->getBaseOfficeData();
        $data['security_type'] = null;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getOfficeRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with null security type. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_fails_with_invalid_fire_protection_system(): void
    {
        $data = $this->getBaseOfficeData();
        $data['fire_protection_system'] = [999]; // Invalid fire protection system (not in enum)
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getOfficeRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with invalid fire protection system'
        );
        $this->assertArrayHasKey('fire_protection_system.0', $validator->errors()->toArray());
    }

    public function test_it_fails_with_invalid_security_type(): void
    {
        $data = $this->getBaseOfficeData();
        $data['security_type'] = [999]; // Invalid security type (not in enum)
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getOfficeRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with invalid security type'
        );
        $this->assertArrayHasKey('security_type.0', $validator->errors()->toArray());
    }

    public function test_it_fails_with_invalid_height_between_floors(): void
    {
        $data = $this->getBaseOfficeData();
        $data['height_between_floors'] = 0.5; // Below minimum of 1
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getOfficeRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with invalid height between floors'
        );
        $this->assertArrayHasKey('height_between_floors', $validator->errors()->toArray());
    }

    public function test_it_validates_multiple_office_fields(): void
    {
        $data = $this->getBaseOfficeData();
        $data['fire_protection_system'] = [
            FireProtectionSystemEnum::SPRINKLERS->value,
            FireProtectionSystemEnum::HYDRANTS->value
        ];
        $data['height_between_floors'] = 3.2;
        $data['min_area_divisible'] = 75.5;
        $data['number_of_elevators'] = 6;
        $data['office_age'] = 15;
        $data['security_type'] = [
            SecurityTypeEnum::CCTV->value,
            SecurityTypeEnum::SECURITY_GUARD->value
        ];
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getOfficeRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with multiple valid office fields. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }
}

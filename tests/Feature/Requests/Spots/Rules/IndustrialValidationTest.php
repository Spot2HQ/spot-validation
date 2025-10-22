<?php

namespace Spot2HQ\SpotValidation\Tests\Feature\Requests\Spots\Rules;

use Illuminate\Support\Facades\Validator;
use Spot2HQ\SpotValidation\Enums\Spot\BuildingTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\FireProtectionSystemEnum;
use Spot2HQ\SpotValidation\Enums\Spot\LuminaryTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\OfficeAreaPercentageModalityEnum;
use Spot2HQ\SpotValidation\Enums\Spot\SecurityTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\SpotTypeEnum;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\IndustrialRules;
use Spot2HQ\SpotValidation\Tests\Helpers\ValidationRuleExtractor;
use Spot2HQ\SpotValidation\Tests\TestCase;

/**
 * Test Industrial-specific validation rules
 * 
 * @group feature
 * @group validation
 * @group industrial
 */
class IndustrialValidationTest extends TestCase
{
    protected function getBaseIndustrialData(): array
    {
        return [
            'name' => 'Industrial Warehouse',
            'spot_type_id' => SpotTypeEnum::INDUSTRIAL->value,
            'square_space' => 5000.00,
            'is_complex' => 0,
        ];
    }

    public function test_industrial_rules_trait_exists(): void
    {
        // Simple test to verify the IndustrialRules trait exists and can be used
        $this->assertTrue(trait_exists(IndustrialRules::class));
    }

    public function test_industrial_rules_trait_can_be_used(): void
    {
        // Extract actual rules from the trait
        $rules = ValidationRuleExtractor::getIndustrialRules();
        
        $this->assertIsArray($rules);
        $this->assertArrayHasKey('building_type', $rules);
        $this->assertArrayHasKey('office_area_percent_modality', $rules);
        $this->assertArrayHasKey('office_area_percent', $rules);
        $this->assertArrayHasKey('luminary_type', $rules);
    }

    public function test_it_validates_industrial_building_type(): void
    {
        $data = $this->getBaseIndustrialData();
        $data['building_type'] = BuildingTypeEnum::STEEL_AND_CONCRETE->value;
        
        // Extract actual rules from the trait and simplify for package testing
        $allRules = ValidationRuleExtractor::getIndustrialRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid building type. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_office_area_percent_modality(): void
    {
        $data = $this->getBaseIndustrialData();
        
        // Valid modality (1 = percentage)
        $data['office_area_percent_modality'] = OfficeAreaPercentageModalityEnum::PERCENTAGE->value;
        $data['office_area_percent'] = 25.5;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getIndustrialRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid office area percent modality. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_allows_square_meters_for_office_area(): void
    {
        $data = $this->getBaseIndustrialData();
        
        // Valid modality (2 = square meters)
        $data['office_area_percent_modality'] = OfficeAreaPercentageModalityEnum::SQUARE_METERS->value;
        $data['office_area_percent'] = 50; // 50 square meters is valid (within max:100)
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getIndustrialRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with square meters modality. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_luminary_type(): void
    {
        $data = $this->getBaseIndustrialData();
        $data['luminary_type'] = LuminaryTypeEnum::LED->value;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getIndustrialRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid luminary type. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_max_height_greater_than_height(): void
    {
        $data = $this->getBaseIndustrialData();
        $data['height'] = 5.0;
        $data['max_height'] = 8.0;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getIndustrialRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid height values. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_charging_ports_within_range(): void
    {
        $data = $this->getBaseIndustrialData();
        $data['charging_ports'] = 10;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getIndustrialRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid charging ports. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_vehicle_ramp_within_range(): void
    {
        $data = $this->getBaseIndustrialData();
        $data['vehicle_ramp'] = 5;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getIndustrialRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid vehicle ramp. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_fire_protection_system_array(): void
    {
        $data = $this->getBaseIndustrialData();
        $data['fire_protection_system'] = [
            FireProtectionSystemEnum::SPRINKLERS->value,
            FireProtectionSystemEnum::HYDRANTS->value,
            FireProtectionSystemEnum::FIRE_EXTINGUISHERS->value
        ];
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getIndustrialRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid fire protection system. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_security_type_array(): void
    {
        $data = $this->getBaseIndustrialData();
        $data['security_type'] = [
            SecurityTypeEnum::CCTV->value,
            SecurityTypeEnum::GUARD_BOOTH->value
        ];
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getIndustrialRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid security type. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_fails_with_invalid_building_type(): void
    {
        $data = $this->getBaseIndustrialData();
        $data['building_type'] = 999; // Invalid building type
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getIndustrialRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with invalid building type'
        );
        $this->assertArrayHasKey('building_type', $validator->errors()->toArray());
    }

    public function test_it_fails_with_invalid_luminary_type(): void
    {
        $data = $this->getBaseIndustrialData();
        $data['luminary_type'] = 999; // Invalid luminary type (not in enum)
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getIndustrialRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with invalid luminary type'
        );
        $this->assertArrayHasKey('luminary_type', $validator->errors()->toArray());
    }
}
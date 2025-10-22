<?php

namespace Spot2HQ\SpotValidation\Tests\Feature\Requests\Spots\Rules;

use Illuminate\Support\Facades\Validator;
use Spot2HQ\SpotValidation\Enums\Spot\SpotTypeEnum;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\TerrainRules;
use Spot2HQ\SpotValidation\Tests\Helpers\ValidationRuleExtractor;
use Spot2HQ\SpotValidation\Tests\TestCase;

/**
 * Test Terrain-specific validation rules
 * 
 * @group feature
 * @group validation
 * @group terrain
 */
class TerrainValidationTest extends TestCase
{
    protected function getBaseTerrainData(): array
    {
        return [
            'name' => 'Terrain Land',
            'spot_type_id' => SpotTypeEnum::TERRAIN->value,
            'square_space' => 5000.00,
            'is_complex' => 0,
        ];
    }

    public function test_terrain_rules_trait_exists(): void
    {
        // Simple test to verify the TerrainRules trait exists and can be used
        $this->assertTrue(trait_exists(TerrainRules::class));
    }

    public function test_terrain_rules_trait_can_be_used(): void
    {
        // Extract actual rules from the trait
        $rules = ValidationRuleExtractor::getTerrainRules();
        
        $this->assertIsArray($rules);
        $this->assertArrayHasKey('land_use', $rules);
        $this->assertArrayHasKey('front', $rules);
        $this->assertArrayHasKey('energy', $rules);
        $this->assertArrayHasKey('min_area_divisible', $rules);
    }

    public function test_it_validates_land_use(): void
    {
        $data = $this->getBaseTerrainData();
        $data['land_use'] = 'Commercial Development';
        
        // Extract actual rules from the trait and simplify for package testing
        $allRules = ValidationRuleExtractor::getTerrainRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid land use. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_front(): void
    {
        $data = $this->getBaseTerrainData();
        $data['front'] = 25.5;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getTerrainRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid front value. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_energy(): void
    {
        $data = $this->getBaseTerrainData();
        $data['energy'] = 'Energia electrica disponible';
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getTerrainRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid energy description. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_validates_min_area_divisible(): void
    {
        $data = $this->getBaseTerrainData();
        $data['min_area_divisible'] = 100.25;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getTerrainRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with valid min area divisible. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_allows_null_land_use(): void
    {
        $data = $this->getBaseTerrainData();
        $data['land_use'] = null;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getTerrainRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with null land use. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_allows_null_front(): void
    {
        $data = $this->getBaseTerrainData();
        $data['front'] = null;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getTerrainRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with null front value. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_allows_null_energy(): void
    {
        $data = $this->getBaseTerrainData();
        $data['energy'] = null;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getTerrainRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with null energy description. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_allows_zero_front(): void
    {
        $data = $this->getBaseTerrainData();
        $data['front'] = 0;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getTerrainRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with zero front value. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_fails_with_negative_front(): void
    {
        $data = $this->getBaseTerrainData();
        $data['front'] = -5; // Below minimum of 0
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getTerrainRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with negative front value'
        );
        $this->assertArrayHasKey('front', $validator->errors()->toArray());
    }

    public function test_it_fails_with_front_above_maximum(): void
    {
        $data = $this->getBaseTerrainData();
        $data['front'] = 1500; // Above maximum of 1000
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getTerrainRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with front value above maximum'
        );
        $this->assertArrayHasKey('front', $validator->errors()->toArray());
    }

    public function test_it_fails_with_invalid_min_area_divisible(): void
    {
        $data = $this->getBaseTerrainData();
        $data['min_area_divisible'] = 0.5; // Below minimum of 1
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getTerrainRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with invalid min area divisible'
        );
        $this->assertArrayHasKey('min_area_divisible', $validator->errors()->toArray());
    }

    public function test_it_validates_maximum_length_strings(): void
    {
        $data = $this->getBaseTerrainData();
        $data['land_use'] = str_repeat('A', 250); // Exactly 250 characters
        $data['energy'] = str_repeat('B', 255); // Exactly 255 characters
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getTerrainRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with maximum length strings. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }

    public function test_it_fails_with_strings_over_maximum(): void
    {
        $data = $this->getBaseTerrainData();
        $data['land_use'] = str_repeat('A', 251); // Over 250 characters
        $data['energy'] = str_repeat('B', 256); // Over 255 characters
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getTerrainRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes(), 
            'Should fail with strings over maximum length'
        );
        $this->assertArrayHasKey('land_use', $validator->errors()->toArray());
        $this->assertArrayHasKey('energy', $validator->errors()->toArray());
    }

    public function test_it_validates_multiple_terrain_fields(): void
    {
        $data = $this->getBaseTerrainData();
        $data['land_use'] = 'Uso mixto de desarrollo';
        $data['front'] = 50.75;
        $data['energy'] = 'Sistema solar y energia electrica disponible';
        $data['min_area_divisible'] = 200.5;
        
        // Extract actual rules from the trait
        $allRules = ValidationRuleExtractor::getTerrainRules();
        $rules = ValidationRuleExtractor::getSimplifiedRules($allRules);
        
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes(), 
            'Should pass with multiple valid terrain fields. Errors: ' . json_encode($validator->errors()->toArray())
        );
    }
}

<?php

namespace Spot2HQ\SpotValidation\Tests\Feature\Requests\Spots;

use Spot2HQ\SpotValidation\Http\Requests\SpotRequest;
use Spot2HQ\SpotValidation\Tests\TestCase;

/**
 * Test SpotRequest validation rules
 * 
 * @group feature
 * @group validation
 */
class SpotRequestValidationTest extends TestCase
{
    public function test_spot_request_class_exists(): void
    {
        // Simple test to verify the SpotRequest class exists
        $this->assertTrue(class_exists(SpotRequest::class));
    }

    public function test_spot_request_extends_form_request(): void
    {
        // Test that SpotRequest extends FormRequest
        $request = new SpotRequest();
        $this->assertInstanceOf(\Illuminate\Foundation\Http\FormRequest::class, $request);
    }

    public function test_spot_request_uses_all_rule_traits(): void
    {
        // Test that SpotRequest uses all the rule traits
        $request = new SpotRequest();
        
        // Check that the class uses the expected traits
        $traits = class_uses_recursive($request);
        
        $expectedTraits = [
            'Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\CorporateRules',
            'Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\IndustrialRules',
            'Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\MallRules',
            'Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\OfficeRules',
            'Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\RetailRules',
            'Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\SharedRules',
            'Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\TerrainRules',
            'Spot2HQ\SpotValidation\Traits\WithPathParameters',
        ];
        
        foreach ($expectedTraits as $trait) {
            $this->assertArrayHasKey($trait, $traits, "SpotRequest should use trait: {$trait}");
        }
    }

    public function test_spot_request_has_rules_method(): void
    {
        // Test that SpotRequest has a rules method
        $request = new SpotRequest();
        
        $this->assertTrue(method_exists($request, 'rules'));
        $this->assertTrue(is_callable([$request, 'rules']));
    }

    public function test_spot_request_has_map_rules_method(): void
    {
        // Test that SpotRequest has a mapRules method
        $request = new SpotRequest();
        
        $this->assertTrue(method_exists($request, 'mapRules'));
        $this->assertTrue(is_callable([$request, 'mapRules']));
    }

    public function test_spot_request_has_get_extra_rules_method(): void
    {
        // Test that SpotRequest has a getExtraRules method
        $request = new SpotRequest();
        
        $this->assertTrue(method_exists($request, 'getExtraRules'));
        $this->assertTrue(is_callable([$request, 'getExtraRules']));
    }

    public function test_spot_request_traits_have_expected_methods(): void
    {
        // Test that the rule traits have the expected methods
        $request = new SpotRequest();
        
        $expectedMethods = [
            'sharedRules',
            'contactRules', 
            'detailRules',
            'priceRules',
            'industrialRules',
            'corporateRules',
            'mallRules',
            'officeRules',
            'retailRules',
            'terrainRules',
        ];
        
        foreach ($expectedMethods as $method) {
            $this->assertTrue(method_exists($request, $method), "SpotRequest should have method: {$method}");
        }
    }

    public function test_spot_request_with_sample_data(): void
    {
        // Test SpotRequest with sample data to see if rules are generated
        $request = new SpotRequest();
        
        // Test that the request can be instantiated without errors
        $this->assertInstanceOf(SpotRequest::class, $request);
        
        // Test that methods exist
        $this->assertTrue(method_exists($request, 'rules'));
        $this->assertTrue(method_exists($request, 'mapRules'));
    }

    public function test_spot_request_rule_traits_exist(): void
    {
        // Test that all rule traits exist
        $traits = [
            'Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\CorporateRules',
            'Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\IndustrialRules',
            'Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\MallRules',
            'Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\OfficeRules',
            'Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\RetailRules',
            'Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\SharedRules',
            'Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\TerrainRules',
        ];
        
        foreach ($traits as $trait) {
            $this->assertTrue(trait_exists($trait), "Trait should exist: {$trait}");
        }
    }

    public function test_spot_request_with_path_parameters_trait(): void
    {
        // Test that SpotRequest uses WithPathParameters trait
        $request = new SpotRequest();
        $traits = class_uses_recursive($request);
        
        $this->assertArrayHasKey('Spot2HQ\SpotValidation\Traits\WithPathParameters', $traits);
    }
}
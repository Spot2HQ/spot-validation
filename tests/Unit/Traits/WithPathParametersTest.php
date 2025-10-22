<?php

namespace Spot2HQ\SpotValidation\Tests\Unit\Traits;

use Spot2HQ\SpotValidation\Tests\TestCase;
use Spot2HQ\SpotValidation\Traits\WithPathParameters;

/**
 * Test WithPathParameters trait
 * 
 * @group unit
 * @group traits
 */
class WithPathParametersTest extends TestCase
{
    public function test_trait_can_be_used(): void
    {
        // Create a mock request class that uses the trait
        $request = new class extends \Illuminate\Http\Request {
            use WithPathParameters;
            
            public function route($param = null, $default = null)
            {
                return null; // No route for this test
            }
        };
        
        // Test that the trait works when route is null
        $request->merge(['test' => 'value']);
        
        $all = $request->all();
        
        $this->assertArrayHasKey('test', $all);
        $this->assertEquals('value', $all['test']);
    }

    public function test_trait_exists(): void
    {
        // Simple test to verify the trait exists and can be used
        $this->assertTrue(trait_exists(WithPathParameters::class));
    }
}
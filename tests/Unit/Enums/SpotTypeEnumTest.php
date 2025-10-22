<?php

namespace Spot2HQ\SpotValidation\Tests\Unit\Enums;

use Spot2HQ\SpotValidation\Tests\TestCase;
use Spot2HQ\SpotValidation\Enums\Spot\SpotTypeEnum;

/**
 * Test SpotTypeEnum functionality
 * 
 * @group unit
 * @group enums
 */
class SpotTypeEnumTest extends TestCase
{
    /** @test */
    public function test_it_has_correct_enum_values(): void
    {
        $this->assertSame(9, SpotTypeEnum::INDUSTRIAL->value);
        $this->assertSame(11, SpotTypeEnum::OFFICE->value);
        $this->assertSame(13, SpotTypeEnum::RETAIL->value);
        $this->assertSame(15, SpotTypeEnum::TERRAIN->value);
    }

    /** @test */
    public function test_it_returns_correct_labels(): void
    {
        $this->assertSame('Industrial', SpotTypeEnum::INDUSTRIAL->label());
        $this->assertSame('Oficinas', SpotTypeEnum::OFFICE->label());
        $this->assertSame('Local Comercial', SpotTypeEnum::RETAIL->label());
        $this->assertSame('Terrenos', SpotTypeEnum::TERRAIN->label());
    }

    /** @test */
    public function test_it_returns_valid_spot_types_values(): void
    {
        $validTypes = SpotTypeEnum::getValidSpotTypesValues();
        
        $this->assertIsArray($validTypes);
        $this->assertCount(4, $validTypes);
        $this->assertContains(13, $validTypes); // RETAIL
        $this->assertContains(9, $validTypes);  // INDUSTRIAL
        $this->assertContains(11, $validTypes); // OFFICE
        $this->assertContains(15, $validTypes); // TERRAIN
    }

    /** @test */
    public function test_it_returns_landing_page_spot_types_values(): void
    {
        $landingTypes = SpotTypeEnum::getValidLandingPageSpotTypesValues();
        
        $this->assertIsArray($landingTypes);
        $this->assertCount(3, $landingTypes);
        $this->assertNotContains(15, $landingTypes); // TERRAIN should not be included
    }

    /** @test */
    public function test_it_returns_all_cases(): void
    {
        $cases = SpotTypeEnum::cases();
        
        $this->assertGreaterThan(4, count($cases));
        $this->assertContainsOnlyInstancesOf(SpotTypeEnum::class, $cases);
    }

    /** @test */
    public function test_it_can_be_created_from_value(): void
    {
        $enum = SpotTypeEnum::from(9);
        
        $this->assertInstanceOf(SpotTypeEnum::class, $enum);
        $this->assertSame(SpotTypeEnum::INDUSTRIAL, $enum);
    }

    /** @test */
    public function test_it_returns_null_when_trying_from_invalid_value(): void
    {
        $enum = SpotTypeEnum::tryFrom(999);
        
        $this->assertNull($enum);
    }

    /** @test */
    public function test_it_returns_all_industrial_types_values(): void
    {
        $types = SpotTypeEnum::getAllIndustrialTypesValues();
        
        $this->assertIsArray($types);
        $this->assertContains(9, $types);  // INDUSTRIAL
        $this->assertContains(17, $types); // WAREHOUSE
        $this->assertContains(18, $types); // INDUSTRIAL_UNIT
        $this->assertContains(21, $types); // INDUSTRIAL_PARK
    }

    /** @test */
    public function test_it_returns_all_office_types_values(): void
    {
        $types = SpotTypeEnum::getAllOfficeTypesValues();
        
        $this->assertIsArray($types);
        $this->assertContains(11, $types); // OFFICE
        $this->assertContains(20, $types); // CORPORATE
    }

    /** @test */
    public function test_it_returns_all_terrain_types_values(): void
    {
        $types = SpotTypeEnum::getAllTerrainTypesValues();
        
        $this->assertIsArray($types);
        $this->assertContains(10, $types); // TERRAIN_OLD
        $this->assertContains(15, $types); // TERRAIN
    }

    /** @test */
    public function test_it_returns_complexes_types_with_labels(): void
    {
        $types = SpotTypeEnum::getComplexesTypesValues();
        
        $this->assertIsArray($types);
        $this->assertArrayHasKey(9, $types);  // INDUSTRIAL
        $this->assertArrayHasKey(11, $types); // OFFICE
        $this->assertArrayHasKey(13, $types); // RETAIL
        $this->assertArrayHasKey(15, $types); // TERRAIN
        
        $this->assertSame('Industrial', $types[9]);
        $this->assertSame('Oficinas', $types[11]);
    }

    /** @test */
    public function enum_helper_methods_work(): void
    {
        // Test getValues
        $values = SpotTypeEnum::getValues();
        $this->assertIsArray($values);
        $this->assertContains(9, $values);
        
        // Test getKeys
        $keys = SpotTypeEnum::getKeys();
        $this->assertIsArray($keys);
        $this->assertContains('INDUSTRIAL', $keys);
        $this->assertContains('OFFICE', $keys);
        
        // Test toArray
        $array = SpotTypeEnum::toArray();
        $this->assertIsArray($array);
        $this->assertArrayHasKey('INDUSTRIAL', $array);
        $this->assertSame(9, $array['INDUSTRIAL']);
    }

    /** @test */
    public function test_it_can_get_api_response_object(): void
    {
        $response = SpotTypeEnum::INDUSTRIAL->getApiResponseObject();
        
        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('label', $response);
        $this->assertSame(9, $response['id']);
        $this->assertSame('Industrial', $response['label']);
    }
}


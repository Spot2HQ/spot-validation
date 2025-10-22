<?php

namespace Spot2HQ\SpotValidation\Tests\Unit\Enums;

use Spot2HQ\SpotValidation\Tests\TestCase;
use Spot2HQ\SpotValidation\Enums\Spot\BuildingTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\BuildingStatusEnum;
use Spot2HQ\SpotValidation\Enums\Spot\BuildingClassEnum;
use Spot2HQ\SpotValidation\Enums\Spot\BuildingConditionEnum;
use Spot2HQ\SpotValidation\Enums\Spot\SpotStateEnum;

/**
 * Test Spot Catalog Enums
 * 
 * @group unit
 * @group enums
 * @group catalog
 */
class SpotCatalogEnumsTest extends TestCase
{
    /** @test */
    public function test_building_type_enum_has_correct_values_and_labels(): void
    {
        $this->assertSame(1, BuildingTypeEnum::STEEL_AND_CONCRETE->value);
        $this->assertSame('Acero y concreto', BuildingTypeEnum::STEEL_AND_CONCRETE->label());
        
        $this->assertSame(2, BuildingTypeEnum::BLOCK_AND_SHEET->value);
        $this->assertSame('Block y Lámina', BuildingTypeEnum::BLOCK_AND_SHEET->label());
    }

    /** @test */
    public function test_building_status_enum_has_correct_values_and_labels(): void
    {
        $this->assertSame(1, BuildingStatusEnum::COMPLETED->value);
        $this->assertSame('Terminado', BuildingStatusEnum::COMPLETED->label());
        
        $this->assertSame(2, BuildingStatusEnum::UNDER_CONSTRUCTION->value);
        $this->assertSame('En construcción', BuildingStatusEnum::UNDER_CONSTRUCTION->label());
        
        $this->assertSame(3, BuildingStatusEnum::PROJECT->value);
        $this->assertSame('Proyecto', BuildingStatusEnum::PROJECT->label());
    }

    /** @test */
    public function test_building_class_enum_has_correct_values_and_labels(): void
    {
        $this->assertSame(1, BuildingClassEnum::A_PLUS->value);
        $this->assertSame('A+', BuildingClassEnum::A_PLUS->label());
        
        $this->assertSame(2, BuildingClassEnum::A->value);
        $this->assertSame('A', BuildingClassEnum::A->label());
        
        $this->assertSame(3, BuildingClassEnum::B->value);
        $this->assertSame('B', BuildingClassEnum::B->label());
        
        $this->assertSame(4, BuildingClassEnum::C->value);
        $this->assertSame('C', BuildingClassEnum::C->label());
    }

    /** @test */
    public function test_building_condition_enum_has_correct_values_and_labels(): void
    {
        $this->assertSame(1, BuildingConditionEnum::SHELL->value);
        $this->assertSame('Obra gris', BuildingConditionEnum::SHELL->label());
        
        $this->assertSame(2, BuildingConditionEnum::CONDITIONED->value);
        $this->assertSame('Acondicionado', BuildingConditionEnum::CONDITIONED->label());
        
        $this->assertSame(3, BuildingConditionEnum::FURNISHED->value);
        $this->assertSame('Amueblado', BuildingConditionEnum::FURNISHED->label());
    }

    /** @test */
    public function test_spot_state_enum_has_correct_values_and_labels(): void
    {
        $this->assertSame(2, SpotStateEnum::DRAFT->value);
        $this->assertSame('Borrador', SpotStateEnum::DRAFT->label());
        
        $this->assertSame(1, SpotStateEnum::PUBLIC->value);
        $this->assertSame('Público', SpotStateEnum::PUBLIC->label());
        
        $this->assertSame(3, SpotStateEnum::DEACTIVATED->value);
        $this->assertSame('Desactivado', SpotStateEnum::DEACTIVATED->label());
        
        $this->assertSame(4, SpotStateEnum::ARCHIVED->value);
        $this->assertSame('Archivado', SpotStateEnum::ARCHIVED->label());
    }

    /** @test */
    public function test_all_catalog_enums_implement_label_interface(): void
    {
        $enums = [
            BuildingTypeEnum::STEEL_AND_CONCRETE,
            BuildingStatusEnum::COMPLETED,
            BuildingClassEnum::A_PLUS,
            BuildingConditionEnum::SHELL,
            SpotStateEnum::PUBLIC,
        ];

        foreach ($enums as $enum) {
            $this->assertIsString($enum->label());
            $this->assertNotEmpty($enum->label());
        }
    }

    /** @test */
    public function test_all_catalog_enums_can_return_values_array(): void
    {
        $this->assertIsArray(BuildingTypeEnum::getValues());
        $this->assertIsArray(BuildingStatusEnum::getValues());
        $this->assertIsArray(BuildingClassEnum::getValues());
        $this->assertIsArray(BuildingConditionEnum::getValues());
        $this->assertIsArray(SpotStateEnum::getValues());
    }

    /** @test */
    public function test_all_catalog_enums_can_return_keys_array(): void
    {
        $keys = BuildingTypeEnum::getKeys();
        $this->assertIsArray($keys);
        $this->assertContains('STEEL_AND_CONCRETE', $keys);
        $this->assertContains('BLOCK_AND_SHEET', $keys);
    }

    /** @test */
    public function test_catalog_enums_can_be_created_from_value(): void
    {
        $buildingType = BuildingTypeEnum::from(1);
        $this->assertSame(BuildingTypeEnum::STEEL_AND_CONCRETE, $buildingType);

        $status = BuildingStatusEnum::from(2);
        $this->assertSame(BuildingStatusEnum::UNDER_CONSTRUCTION, $status);

        $class = BuildingClassEnum::from(1);
        $this->assertSame(BuildingClassEnum::A_PLUS, $class);
    }

    /** @test */
    public function test_catalog_enums_return_null_for_invalid_values(): void
    {
        $this->assertNull(BuildingTypeEnum::tryFrom(999));
        $this->assertNull(BuildingStatusEnum::tryFrom(999));
        $this->assertNull(BuildingClassEnum::tryFrom(999));
    }
}


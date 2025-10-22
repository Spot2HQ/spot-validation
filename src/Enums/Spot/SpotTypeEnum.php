<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for spot types
 * 
 * This enum represents all the different types of spots (properties)
 * in the system, including current and legacy types.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum SpotTypeEnum: int implements LabelInterface
{
    use EnumHelper;
    case INDUSTRIAL = 9;
    case OFFICE = 11;
    case RETAIL = 13;
    case TERRAIN = 15;
    // old types
    case STREET_LEVEL = 1;
    case LOCAL_IN_SHOPPING_CENTER = 2;
    case ISLAND_IN_SHOPPING_CENTER = 3;
    case OTHER = 4;
    case LOCAL_IN_BUILDING = 5;
    case BAZAR = 6;
    case CONCEPT_STORE = 7;
    case DARK_KITCHEN = 8;
    case TERRAIN_OLD = 10;
    case WAREHOUSE = 17;
    case INDUSTRIAL_UNIT = 18;
    case SHOPPING_CENTER = 19;
    case CORPORATE = 20;
    case INDUSTRIAL_PARK = 21;

    /**
     * Return the array of ids of cases,
     * is needed to provide the ->value clause
     */
    public static function getValidSpotTypesValues(): array
    {
        return [
            self::RETAIL->value,
            self::INDUSTRIAL->value,
            self::OFFICE->value,
            self::TERRAIN->value,
        ];
    }

    /**
     * Return the array of ids of cases,
     * is needed to provide the ->value clause
     */
    public static function getValidLandingPageSpotTypesValues(): array
    {
        return [
            self::RETAIL->value,
            self::INDUSTRIAL->value,
            self::OFFICE->value,
        ];
    }

    /**
     * Get the array of ids of cases,
     * is needed to provide the ->value clause
     */
    public static function getComplexesTypesValues(): array
    {
        return [
            self::INDUSTRIAL->value => 'Industrial',
            self::OFFICE->value => 'Oficinas',
            self::RETAIL->value => 'Local Comercial',
            self::TERRAIN->value => 'Terrenos',
        ];
    }

    /**
     * Get the array of ids of cases,
     * is needed to provide the ->value clause
     */
    public static function getAllIndustrialTypesValues(): array
    {
        return [
            self::INDUSTRIAL->value,
            self::WAREHOUSE->value,
            self::INDUSTRIAL_UNIT->value,
            self::INDUSTRIAL_PARK->value,
        ];
    }

    /**
     * Get the array of ids of cases,
     * is needed to provide the ->value clause
     */
    public static function getAllOfficeTypesValues(): array
    {
        return [
            self::OFFICE->value,
            self::CORPORATE->value,
        ];
    }

    /**
     * Get the array of ids of cases,
     * is needed to provide the ->value clause
     */
    public static function getAllTerrainTypesValues(): array
    {
        return [
            self::TERRAIN_OLD->value,
            self::TERRAIN->value,
        ];
    }

    /**
     * Get the array of ids of cases,
     * is needed to provide the ->value clause
     */
    public static function getAllComplexRetailTypesValues(): array
    {
        return [
            self::RETAIL->value,
            self::SHOPPING_CENTER->value,
        ];
    }

    /**
     * Get the array of ids of cases,
     * is needed to provide the ->value clause
     */
    public static function getAllComplexIndustrialTypesValues(): array
    {
        return [
            self::INDUSTRIAL->value,
            self::INDUSTRIAL_PARK->value,
        ];

    }

    /**
     * Get the human-readable label for the spot type
     * 
     * @return string The label for this spot type
     */
    public function label(): string
    {
        return match ($this) {
            SpotTypeEnum::INDUSTRIAL => 'Industrial',
            SpotTypeEnum::OFFICE => 'Oficinas',
            SpotTypeEnum::RETAIL => 'Local Comercial',
            SpotTypeEnum::TERRAIN => 'Terrenos',
            SpotTypeEnum::STREET_LEVEL => 'Local a pie de calle',
            SpotTypeEnum::LOCAL_IN_SHOPPING_CENTER => 'Local en centro comercial',
            SpotTypeEnum::ISLAND_IN_SHOPPING_CENTER => 'Isla en centro comercial',
            SpotTypeEnum::OTHER => 'Otro',
            SpotTypeEnum::LOCAL_IN_BUILDING => 'Local en edificio',
            SpotTypeEnum::BAZAR => 'Bazar',
            SpotTypeEnum::CONCEPT_STORE => 'Concept Store',
            SpotTypeEnum::DARK_KITCHEN => 'Dark Kitchen',
            SpotTypeEnum::TERRAIN_OLD => 'Terreno',
            SpotTypeEnum::WAREHOUSE => 'Bodega',
            SpotTypeEnum::INDUSTRIAL_UNIT => 'Nave industrial',
            SpotTypeEnum::SHOPPING_CENTER => 'Centro comercial',
            SpotTypeEnum::CORPORATE => 'Corporativo',
            SpotTypeEnum::INDUSTRIAL_PARK => 'Parque industrial',
        };
    }
}

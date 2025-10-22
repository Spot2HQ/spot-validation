<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for floor levels
 * 
 * This enum represents the different floor levels or types
 * available in buildings (from basements to penthouses).
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum FloorLevelEnum: int implements LabelInterface
{
    use EnumHelper;

    case BASEMENT_3 = 1;
    case BASEMENT_2 = 2;
    case BASEMENT_1 = 3;
    case GROUND_FLOOR = 4;
    case LOBBY = 5;
    case FLOOR_NUMBER = 6;
    case MEZZANINE = 7;
    case PENTHOUSE = 8;
    case TERRACE = 9;
    case SKY_LOBBY = 10;

    /**
     * Get the human-readable label for the floor level
     * 
     * @return string The label for this floor level
     */
    public function label(): string
    {
        return match ($this) {
            self::BASEMENT_3 => 'Sótano 3',
            self::BASEMENT_2 => 'Sótano 2',
            self::BASEMENT_1 => 'Sótano 1',
            self::GROUND_FLOOR => 'Planta Baja',
            self::LOBBY => 'Lobby',
            self::FLOOR_NUMBER => 'Número de Piso (del 1 al 99)',
            self::MEZZANINE => 'Mezzanine',
            self::PENTHOUSE => 'Penthouse',
            self::TERRACE => 'Terraza',
            self::SKY_LOBBY => 'Sky Lobby',
        };
    }
}


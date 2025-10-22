<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for luminary (lighting) types
 * 
 * This enum represents the different types of lighting systems
 * used in buildings.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum LuminaryTypeEnum: int implements LabelInterface
{
    use EnumHelper;

    case LED = 1;
    case T5 = 2;
    case FLUORESCENT = 3;
    case HID = 4;

    /**
     * Get the human-readable label for the luminary type
     * 
     * @return string The label for this luminary type
     */
    public function label(): string
    {
        return match ($this) {
            self::LED => 'LED',
            self::T5 => 'T-5',
            self::FLUORESCENT => 'Fluorescente',
            self::HID => 'HID',
        };
    }
}


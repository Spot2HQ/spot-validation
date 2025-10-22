<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for office area calculation modality
 * 
 * This enum represents the different modalities for expressing
 * office area (as percentage or square meters).
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum OfficeAreaPercentageModalityEnum: int implements LabelInterface
{
    use EnumHelper;

    case PERCENTAGE = 1;
    case SQUARE_METERS = 2;

    /**
     * Get the human-readable label for the office area modality
     * 
     * @return string The label for this office area modality
     */
    public function label(): string
    {
        return match ($this) {
            self::PERCENTAGE => 'Porcentaje',
            self::SQUARE_METERS => 'Metros cuadrados',
        };
    }
}


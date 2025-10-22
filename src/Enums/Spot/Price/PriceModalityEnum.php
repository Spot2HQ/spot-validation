<?php

namespace Spot2HQ\SpotValidation\Enums\Spot\Price;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for price payment modalities
 * 
 * This enum represents the different time periods for price
 * payment frequency (daily/weekly, monthly, or annual).
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot\Price
 */
enum PriceModalityEnum: int implements LabelInterface
{
    use EnumHelper;

    case DAILY_WEEKLY = 1;
    case MONTHLY = 2;
    case ANNUAL = 3;

    /**
     * Get the human-readable label for the price modality
     * 
     * @return string The label for this price modality
     */
    public function label(): string
    {
        return match ($this) {
            self::DAILY_WEEKLY => 'diario/semanal',
            self::MONTHLY => 'mensual',
            self::ANNUAL => 'anual',
        };
    }
}


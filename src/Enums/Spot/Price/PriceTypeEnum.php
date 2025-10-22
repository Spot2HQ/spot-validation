<?php

namespace Spot2HQ\SpotValidation\Enums\Spot\Price;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for price modality types (Rent, Sale, or Both)
 * 
 * This enum represents the different types of price modalities
 * available for spots in the system.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot\Price
 */
enum PriceTypeEnum: int implements LabelInterface
{
    use EnumHelper;

    case RENT = 1;
    case SALE = 2;
    case RENT_AND_SALE = 3;

    /**
     * Get the human-readable label for the modality type
     * 
     * @return string The label for this modality type
     */
    public function label(): string
    {
        return match ($this) {
            self::RENT => 'Renta',
            self::SALE => 'Venta',
            self::RENT_AND_SALE => 'Renta y Venta',
        };
    }
}


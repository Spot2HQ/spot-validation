<?php

namespace Spot2HQ\SpotValidation\Enums\Spot\Price;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for maintenance fee types
 * 
 * This enum represents the different ways maintenance fees
 * can be calculated or expressed in the system.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot\Price
 */
enum PriceMaintenanceTypeEnum: int implements LabelInterface
{
    use EnumHelper;

    case REAL_VALUE = 1;
    case PERCENTAGE_VALUE = 2;
    case SQUARE_METER_VALUE = 3;
    case TO_BE_DEFINED = 4;
    case INCLUDED_IN_RENT = 5;

    /**
     * Get the human-readable label for the maintenance type
     * 
     * @return string The label for this maintenance type
     */
    public function label(): string
    {
        return match ($this) {
            self::REAL_VALUE => 'Valor real',
            self::PERCENTAGE_VALUE => 'Valor porcentual',
            self::SQUARE_METER_VALUE => 'Valor por metro cuadrado',
            self::TO_BE_DEFINED => 'Por definir',
            self::INCLUDED_IN_RENT => 'Incluido en la renta',
        };
    }
}


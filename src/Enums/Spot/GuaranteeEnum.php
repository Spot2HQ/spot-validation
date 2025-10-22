<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for guarantee types
 * 
 * This enum represents the different types of guarantees
 * that can be used for rental agreements.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum GuaranteeEnum: int implements LabelInterface
{
    use EnumHelper;

    case GUARANTOR = 1;
    case JOINT_OBLIGOR = 2;
    case BOND = 3;
    case LETTER_OF_CREDIT = 4;
    case OTHER = 5;

    /**
     * Get the human-readable label for the guarantee type
     * 
     * @return string The label for this guarantee type
     */
    public function label(): string
    {
        return match ($this) {
            self::GUARANTOR => 'Aval',
            self::JOINT_OBLIGOR => 'Obligado solidario',
            self::BOND => 'Fianza',
            self::LETTER_OF_CREDIT => 'Carta crédito',
            self::OTHER => 'Otro',
        };
    }
}


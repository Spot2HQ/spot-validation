<?php

namespace Spot2HQ\SpotValidation\Enums\Spot\Price;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for currency types
 * 
 * This enum represents the different currencies supported
 * in the system for price calculations.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot\Price
 */
enum PriceCurrencyTypeEnum: int implements LabelInterface
{
    use EnumHelper;

    case MXN = 1;
    case USD = 2;

    /**
     * Get the human-readable label for the currency
     * 
     * @return string The currency code
     */
    public function label(): string
    {
        return match ($this) {
            self::MXN => 'MXN',
            self::USD => 'USD',
        };
    }

    /**
     * Get the currency symbol
     * 
     * @return string The symbol for this currency
     */
    public function symbol(): string
    {
        return match ($this) {
            self::MXN => '$',
            self::USD => 'USD $',
        };
    }
}


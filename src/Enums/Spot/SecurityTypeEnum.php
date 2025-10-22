<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for security system types
 * 
 * This enum represents the different types of security systems
 * available in buildings.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum SecurityTypeEnum: int implements LabelInterface
{
    use EnumHelper;

    case CCTV = 1;
    case GUARD_BOOTH = 2;
    case SECURITY_GUARD = 3;

    /**
     * Get the human-readable label for the security type
     * 
     * @return string The label for this security type
     */
    public function label(): string
    {
        return match ($this) {
            self::CCTV => 'CCTV',
            self::GUARD_BOOTH => 'Caseta de vigilancia',
            self::SECURITY_GUARD => 'Vigilante',
        };
    }
}


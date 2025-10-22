<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for spot publication states
 * 
 * This enum represents the different publication states
 * for spots in the system (draft, public, deactivated, archived).
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum SpotStateEnum: int implements LabelInterface
{
    use EnumHelper;

    case DRAFT = 2;
    case PUBLIC = 1;
    case DEACTIVATED = 3;
    case ARCHIVED = 4;

    /**
     * Get the human-readable label for the spot state
     * 
     * @return string The label for this spot state
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Borrador',
            self::PUBLIC => 'Público',
            self::DEACTIVATED => 'Desactivado',
            self::ARCHIVED => 'Archivado',
        };
    }
}


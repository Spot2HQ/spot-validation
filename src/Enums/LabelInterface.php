<?php

namespace Spot2HQ\SpotValidation\Enums;

interface LabelInterface
{
    /**
     * This method is created in order to provide
     * more context to the IDE, also to more verbose
     * when implementing the EnumHelper trait
     *
     * @see EnumHelper
     */
    public function label(): mixed;
}

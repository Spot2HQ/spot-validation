<?php

namespace Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core;

use Spot2HQ\SpotValidation\Enums\Spot\FireProtectionSystemEnum;
use Spot2HQ\SpotValidation\Enums\Spot\SecurityTypeEnum;
use Illuminate\Validation\Rule;

trait OfficeRules
{
    public function officeRules(): array
    {
        return [
            'fire_protection_system' => 'sometimes|nullable|array',
            'fire_protection_system.*' => ['numeric', Rule::in(FireProtectionSystemEnum::getValues())],
            'height_between_floors' => 'sometimes|nullable|numeric|min:1',
            'min_area_divisible' => 'sometimes|numeric|min:1',
            'number_of_elevators' => 'sometimes|nullable|numeric|min:1',
            'office_age' => 'sometimes|nullable|numeric|min:1',
            'security_type' => 'sometimes|nullable|array',
            'security_type.*' => ['numeric', Rule::in(SecurityTypeEnum::getValues())],
        ];
    }
}

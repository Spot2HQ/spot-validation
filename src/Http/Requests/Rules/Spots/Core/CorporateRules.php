<?php

namespace Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core;

use Spot2HQ\SpotValidation\Enums\Spot\BuildingClassEnum;
use Illuminate\Validation\Rule;

trait CorporateRules
{
    public function corporateRules(): array
    {
        return [
            'average_floor_size' => 'sometimes|numeric|min:1',
            'certification' => 'sometimes|nullable|string',
            'class' => ['sometimes', 'nullable', 'numeric', Rule::in(BuildingClassEnum::getValues())],
        ];
    }
}

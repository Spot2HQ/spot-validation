<?php

namespace Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core;

use Spot2HQ\SpotValidation\Enums\Spot\OfficeAreaPercentageModalityEnum;
use Spot2HQ\SpotValidation\Enums\Spot\LuminaryTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\BuildingTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\FireProtectionSystemEnum;
use Spot2HQ\SpotValidation\Enums\Spot\SecurityTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\SpaceBetweenColumnsEnum;
use Spot2HQ\SpotValidation\Enums\Spot\BuildingClassEnum;
use Illuminate\Validation\Rule;

trait IndustrialRules
{
    public function industrialRules(): array
    {
        return [
            'max_height' => 'sometimes|nullable|numeric|min:0|max:1000|gt:height',
            'office_area_percent_modality' => ['sometimes', 'numeric', 'numeric', Rule::in(OfficeAreaPercentageModalityEnum::getValues())],
            'office_area_percent' => ['sometimes', 'nullable', 'numeric', 'min:0',
                Rule::when(
                    $this->input('office_area_percent_modality') == OfficeAreaPercentageModalityEnum::PERCENTAGE->value,
                    'max:100',
                )], // stores the percentage or square meters allowed for office
            'min_area_divisible' => 'sometimes|numeric',
            'luminaries' => 'sometimes|nullable|in:0,1',
            'luminary_type' => ['sometimes', 'nullable', 'numeric', Rule::in(LuminaryTypeEnum::getValues())],
            'natural_light' => 'sometimes|nullable|numeric|min:0|max:100',
            'luminary_specs' => 'sometimes|nullable|required_if:luminaries,>,0',
            'charging_ports' => 'sometimes|numeric|min:0|max:1000',
            'vehicle_ramp' => 'sometimes|numeric|min:0|max:99',
            'door_height' => 'sometimes|nullable|numeric',
            'energy' => 'sometimes|nullable|string|max:255',
            'floor_material' => 'sometimes|nullable|string|max:255',
            'building_type' => ['sometimes', 'numeric', 'numeric', Rule::in(BuildingTypeEnum::getValues())],
            'fire_protection_system' => 'sometimes|nullable|array',
            'fire_protection_system.*' => ['numeric', Rule::in(FireProtectionSystemEnum::getValues())],
            'security_type' => 'sometimes|nullable|array',
            'security_type.*' => ['numeric', Rule::in(SecurityTypeEnum::getValues())],
            'space_between_columns' => ['sometimes', 'nullable', 'numeric', Rule::in(SpaceBetweenColumnsEnum::getValues())],
            'expansion_up_to' => 'sometimes|nullable|in:0,1',
            'possible_bts' => 'sometimes|nullable|in:0,1',
            'certification' => 'sometimes|nullable|string',
            'class' => ['sometimes', 'nullable', 'numeric', Rule::in(BuildingClassEnum::getValues())],
        ];
    }
}

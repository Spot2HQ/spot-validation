<?php

namespace Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core;

use Spot2HQ\SpotValidation\Enums\Spot\SpotTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceCurrencyTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceAreaTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceModalityEnum;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceMaintenanceTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\BuildingStatusEnum;
use Spot2HQ\SpotValidation\Enums\Spot\FloorLevelEnum;
use Spot2HQ\SpotValidation\Enums\Spot\OfficeVerticalHeightEnum;
use Spot2HQ\SpotValidation\Enums\Spot\GuaranteeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\BuildingConditionEnum;
use Spot2HQ\SpotValidation\Enums\Spot\RoofingTypeEnum;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Photos\ValidCloudImage;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\MinPriceByArea;
use Illuminate\Validation\Rule;

trait SharedRules
{
    const int DESCRIPTION_MAX_LENGTH = 450;

    public function sharedRules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:'.self::DESCRIPTION_MAX_LENGTH,
            'pdf_description' => 'sometimes|string|max:'.self::DESCRIPTION_MAX_LENGTH,
            'is_complex' => 'sometimes|in:0,1',
            'parent_id' => 'sometimes|integer|exists:spots,id',
            'spot_type_id' => ['sometimes', Rule::in(SpotTypeEnum::getValidSpotTypesValues())],
            'company' => 'sometimes|integer|exists:companies,id',
            'in_corner' => 'sometimes|nullable|in:0,1',
            'is_exclusive' => 'sometimes|in:0,1',
            'hide_address_data' => 'sometimes|in:0,1',
            'front' => 'sometimes|numeric|min:0|max:1000',
            'height' => 'sometimes|numeric|min:0|max:1000',
            'floor_level' => ['sometimes', 'numeric', Rule::in(FloorLevelEnum::getValues())],
            'floor_level_number' => 'sometimes|required_if:floor_level,6|numeric|min:1|max:99',
            'vertical_height' => ['sometimes', 'numeric', Rule::in(OfficeVerticalHeightEnum::getValues())],
            'vertical_height_number' => 'sometimes|numeric|min:0|max:99',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'street' => 'sometimes|string|max:240|required_with:latitude,longitude',
            'int_number' => 'sometimes|nullable|string|max:6',
            'ext_number' => 'nullable|required_if:spot_type_id,9,11,13|string|max:6',
            'local' => 'sometimes|nullable|string|max:6',
            'zip_code_id' => [
                'numeric',
                'exists:zip_codes,id',
            ],
            'reference' => 'sometimes|string|max:120',
            'square_space' => [
                'sometimes',
                'numeric',
                'min:1',
                'max:99999999',
                function ($attribute, $value) {
                    $decimalCount = strlen(substr(strrchr($value, '.'), 1));
                    if ($decimalCount > 2) {
                        $roundedValue = round($value, 2);
                        $this->merge([$attribute => $roundedValue]);
                    }
                },
            ],
            'construction_date' => 'sometimes|nullable|numeric|min:1900',
            'building_status' => ['sometimes', 'numeric', Rule::in(BuildingStatusEnum::getValues())],
            'built_to' => ['sometimes', 'nullable', 'date_format:d/m/Y', 'before:'.date('Y-m-d', now()->addYears(30)->timestamp)],
            'publish' => 'sometimes|boolean',
            'photo_urls' => 'sometimes|array|max:20',
            'photo_urls.*' => ['sometimes', 'url', new ValidCloudImage],
            'photos' => 'sometimes|array|max:20',
            'photos.*.type' => ['sometimes', 'nullable', 'integer', Rule::in(array_keys(config('photos.types')))],
            'photos.*.file' => 'required_with:photos.*.type|image|mimes:jpeg,png,jpg|max:5120',
            'photos_order' => 'sometimes|array',
            'photos_order.*' => [
                'sometimes',
                'integer',
                'distinct',
                'exists:photos,id',
            ],
            'external_id' => 'string|required_with:external_updated_at|max:20',
            'external_updated_at' => 'string|date|required_with:external_id|before:'.date('Y-m-d H:i'),
        ];
    }

    public function priceRules(): array
    {
        return [
            'modality_type' => ['sometimes', Rule::in(PriceTypeEnum::getValues())],
            'currency_type' => ['nullable', 'required_with:modality_type', Rule::in(PriceCurrencyTypeEnum::getValues())],
            'rent_price' => [
                'nullable',
                'numeric',
                Rule::when(
                    $this->input('rent_price'),
                    new MinPriceByArea(
                        $this->input('square_space') ?? 1,
                        $this->input('rent_price_area') ?? PriceAreaTypeEnum::TOTAL->value,
                        $this->input('currency_type') ?? PriceCurrencyTypeEnum::MXN->value,
                        'renta'
                    )
                ),
                'max:10000000000',
                'regex:/^\d+(\.\d{1,2})?$/', // 2 decimals
                'required_if:modality_type,1,3',
            ],
            'sale_price' => [
                'nullable',
                'numeric',
                Rule::when(
                    $this->input('sale_price'),
                    new MinPriceByArea(
                        $this->input('square_space') ?? 1,
                        $this->input('sale_price_area') ?? PriceAreaTypeEnum::TOTAL->value,
                        $this->input('currency_type') ?? PriceCurrencyTypeEnum::MXN->value,
                        'venta'
                    )
                ),
                'max:10000000000',
                'regex:/^\d+(\.\d{1,2})?$/', // 2 decimals
                'required_if:modality_type,2,3',
            ],
            'rent_price_area' => [
                'nullable',
                'required_with:rent_price',
                'required_if:modality_type,1,3',
                Rule::in(PriceAreaTypeEnum::getValues()),
            ],
            'sale_price_area' => [
                'nullable',
                'required_with:sale_price',
                'required_if:modality_type,2,3',
                Rule::in(PriceAreaTypeEnum::getValues()),
            ],
            'max_rent_price' => 'sometimes|nullable|numeric|min:0|max:10000000000',
            'max_sale_price' => 'sometimes|nullable|numeric|min:0|max:10000000000',
            'modality' => ['sometimes', Rule::in(PriceModalityEnum::getValues())],
            'guarantee_deposit' => 'sometimes|integer|min:0|max:2',
            'maintenance_type' => ['required_with:maintenance', 'nullable', Rule::in(PriceMaintenanceTypeEnum::getValues())],
            'maintenance' => [
                'sometimes',
                'nullable',
                'numeric',
                Rule::when(
                    $this->input('maintenance_type') === PriceMaintenanceTypeEnum::PERCENTAGE_VALUE->value,
                    'max:100'
                ),
            ],
        ];
    }

    public function contactRules(): array
    {
        return [
            'contact_id' => 'sometimes|exists:contacts,id',
        ];
    }

    public function detailRules(): array
    {
        return [
            'amenities' => 'sometimes|nullable|array',
            'amenities.*' => 'sometimes|numeric|distinct|exists:amenities,id',
            'guarantee' => 'sometimes|array',
            'guarantee.*' => ['sometimes', 'numeric', 'distinct', Rule::in(GuaranteeEnum::getValues())],
            'parking_spaces' => 'sometimes|numeric|min:0',
            'parking_space_by_area' => 'sometimes|nullable|numeric',
            'trademarks' => 'sometimes|nullable|array',
            'trademarks.*' => 'sometimes|numeric|distinct|exists:trademarks,id',
            'spot_condition' => ['sometimes', 'numeric', Rule::in(BuildingConditionEnum::getValues())],
            'roofing_type' => ['sometimes', 'nullable', 'numeric', Rule::in(RoofingTypeEnum::getValues())],
            'land_use' => 'sometimes|nullable|string|max:250',
        ];
    }

    public function basicValidationRules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:'.self::DESCRIPTION_MAX_LENGTH,
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'street' => 'sometimes|string|max:240|required_with:latitude,longitude',
            'ext_number' => 'sometimes|nullable|string|max:6',
            'int_number' => 'sometimes|nullable|string|max:6',
            'local' => 'sometimes|nullable|string|max:6',
            'zip_code_id' => [
                'sometimes',
                'numeric',
                'exists:zip_codes,id',
            ],
            'reference' => 'sometimes|string|max:120',
            'is_complex' => 'sometimes|in:0,1',
            'is_exclusive' => 'sometimes|in:0,1',
            'hide_address_data' => 'sometimes|in:0,1',
            'front' => 'sometimes|numeric|min:0|max:1000',
            'height' => 'sometimes|numeric|min:0|max:1000',
            'square_space' => [
                'sometimes',
                'numeric',
                'min:1',
                'max:99999999',
                function ($attribute, $value) {
                    $decimalCount = strlen(substr(strrchr($value, '.'), 1));
                    if ($decimalCount > 2) {
                        $roundedValue = round($value, 2);
                        $this->merge([$attribute => $roundedValue]);
                    }
                },
            ],
            // Photo fields
            'photo_urls' => 'sometimes|array|max:20',
            'photo_urls.*' => 'sometimes|url',
            'publish' => 'sometimes|boolean',
        ];
    }
}

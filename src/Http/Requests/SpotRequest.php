<?php

namespace Spot2HQ\SpotValidation\Http\Requests;

use Spot2HQ\SpotValidation\Enums\Spot\SpotTypeEnum;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\CorporateRules;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\IndustrialRules;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\MallRules;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\OfficeRules;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\RetailRules;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\SharedRules;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\TerrainRules;
use Spot2HQ\SpotValidation\Traits\WithPathParameters;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property mixed $spot
 * @property mixed $zip_code_id
 */
class SpotRequest extends FormRequest
{
    use CorporateRules;
    use IndustrialRules;
    use MallRules;
    use OfficeRules;
    use RetailRules;
    use SharedRules;
    use TerrainRules;
    use WithPathParameters;

    private ?object $spot = null;
    // **************
    //   Validation
    // **************

    protected function prepareForValidation(): void
    {
        $this->merge([
            'amenities' => array_filter((array) $this->get('amenities'), fn ($item) => $item !== '' && $item !== null),
            'guarantee' => array_filter((array) $this->get('guarantee'), fn ($item) => $item !== '' && $item !== null),
            'fire_protection_system' => array_filter((array) $this->get('fire_protection_system'), fn ($item) => $item !== '' && $item !== null),
            'security_type' => array_filter((array) $this->get('security_type'), fn ($item) => $item !== '' && $item !== null),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $is_complex = $this->boolean('is_complex');
        $spot_type_id = $this->get('spot_type_id');

        return $this->mapRules($is_complex, $spot_type_id);
    }

    /**
     * @param bool $plot_rules allows to include extra rules for plots
     * @return array|array[]
     */
    public function mapRules(?bool $is_complex, ?int $spot_type_id, bool $plot_rules = false): array
    {
        $extraRules = $this->getExtraRules($plot_rules);

        if (! is_null($this->spot)) {
            if (empty($this->get('is_complex'))) {
                $is_complex = $this->spot->is_complex;
            }
            if (empty($this->get('spot_type_id'))) {
                $spot_type_id = $this->spot->spot_type_id;
            }
        }

        // spot_type_id is required in creation
        if (is_null($this->spot) && is_null($spot_type_id)) {
            return [
                'spot_type_id' => ['required', Rule::in(SpotTypeEnum::getValidSpotTypesValues())],
            ];
        }

        // zip_code_id is required in creation
        if (is_null($this->spot) && is_null($this->{'zip_code_id'})) {
            return [
                'zip_code_id' => ['required', 'numeric', 'exists:zip_codes,id'],
            ];
        }

        if (! $is_complex) {
            return array_merge($this->sharedRules(), $extraRules, $this->getSingleRules($spot_type_id));
        }

        // complex can't hide address data
        if ($is_complex == true && $this->boolean('hide_address_data')) {
            return [
                'is_complex' => [Rule::prohibitedIf($this->boolean('hide_address_data'))],
            ];
        }

        return array_merge($this->sharedRules(), $extraRules, $this->getComplexRules($spot_type_id));
    }

    public function getExtraRules(bool $plot_rules): array
    {
        $hasPrice = $this->has('modality_type') || $this->has('currency_type');
        $hasContact = $this->has('contact_id') || $this->has('contact_category');

        return array_merge(
            ($hasPrice || $plot_rules) ? $this->priceRules() : [],
            ($hasContact || $plot_rules) ? $this->contactRules() : []
        );
    }

    private function getSingleRules(int $spot_type_id): array
    {
        return match ($spot_type_id) {
            SpotTypeEnum::INDUSTRIAL->value => array_merge($this->detailRules(), $this->industrialRules()),
            SpotTypeEnum::RETAIL->value => array_merge($this->detailRules(), $this->retailRules()),
            SpotTypeEnum::OFFICE->value => array_merge($this->detailRules(), $this->officeRules()),
            SpotTypeEnum::TERRAIN->value => array_merge($this->detailRules(), $this->terrainRules()),
            default => [],
        };
    }

    private function getComplexRules(int $spot_type_id): array
    {
        return match ($spot_type_id) {
            SpotTypeEnum::INDUSTRIAL->value => array_merge($this->detailRules(), $this->industrialRules()),
            SpotTypeEnum::RETAIL->value => array_merge($this->detailRules(), $this->mallRules()),
            SpotTypeEnum::OFFICE->value => array_merge($this->detailRules(), $this->officeRules(), $this->corporateRules()),
            SpotTypeEnum::TERRAIN->value => array_merge($this->detailRules(), $this->terrainRules()),
            default => [],
        };
    }

    /**
     * Attributes for validation messages.
     */
    public function attributes(): array
    {
        return [
            'amenities' => 'amenidades',
            'average_floor_size' => 'tamaño de piso promedio',
            'building_class' => 'clase',
            'building_status' => 'estado de construcción',
            'building_type' => 'tipo de construcción',
            'built_to' => 'fecha de entrega de construcción del inmueble',
            'certification' => 'certificación',
            'charging_ports' => 'puertos de carga',
            'city' => 'ciudad',
            'class' => 'clase',
            'company' => 'empresa a la que pertenece',
            'construction_date' => 'año de construcción del inmueble',
            'contact_id' => 'contacto',
            'currency_type' => 'tipo de moneda',
            'description' => 'descripción',
            'door_height' => 'altura de puerta',
            'energy' => 'energía',
            'expansion_up_to' => 'expansión up to',
            'ext_number' => 'número exterior',
            'fire_protection_system' => 'sistema contra incendios',
            'floor_level_number' => 'piso en el que se encuentra',
            'floor_material' => 'tipo de suelo',
            'front' => 'frente',
            'guarantee' => 'garantías',
            'guarantee_deposit' => 'depósito de garantía',
            'height' => 'altura libre mínima',
            'height_between_floors' => 'altura entre pisos',
            'hide_address_data' => 'ocultar número interior y exterior al público',
            'int_number' => 'número interior',
            'is_complex' => 'valor de la opción del complejo',
            'is_exclusive' => 'exclusividad',
            'latitude' => ' latitud',
            'local' => 'número de local en la dirección',
            'longitude' => 'longitud',
            'luminaries' => 'luminarias',
            'luminary_specs' => 'especificaciones de luminarias',
            'luminary_type' => 'tipo de luminarias',
            'maintenance' => 'mantenimiento',
            'maintenance_type' => 'modalidad de mantenimiento',
            'max_height' => 'altura libre máxima',
            'max_rent_price' => 'precio máximo de renta',
            'max_sale_price' => 'precio máximo de venta',
            'min_area_divisible' => 'área mínima divisible',
            'modality' => 'modalidad del precio',
            'modality_type' => 'tipo de modalidad',
            'name' => 'nombre',
            'natural_light' => 'luz natural',
            'number_of_elevators' => 'número de elevadores',
            'office_area_percent' => 'área de oficina',
            'office_area_percent_modality' => 'modalidad de área de oficina',
            'parking_space_by_area' => 'cajones de estacionamiento por superficie',
            'parking_spaces' => 'cajones de estacionamiento',
            'photo_cover' => 'fotos de portada asociadas al spot',
            'photo_urls' => 'arreglo de urls de fotos asociadas al spot',
            'photos' => 'arreglo de fotos asociadas al spot',
            'possible_bts' => 'posible bts',
            'price_area' => 'precio por área',
            'reference' => ' referencia en la dirección',
            'rent_modality_type' => 'modalidad de renta',
            'rent_price' => 'precio de renta',
            'rent_price_area' => 'tipo de precio de renta',
            'sale_modality_type' => 'modalidad de venta',
            'sale_price' => 'precio de venta',
            'sale_price_area' => 'tipo de precio de venta',
            'security_type' => 'tipo de seguridad',
            'space_between_columns' => 'espacio entre columnas',
            'spot_condition' => 'condición de la propiedad',
            'spot_type_id' => 'tipo de spot',
            'square_space' => 'área total',
            'state' => 'estado',
            'street' => 'nombre de calle',
            'trademarks' => 'anclas',
            'vehicle_ramp' => 'rampa vehicular',
            'vertical_height' => 'total de niveles del inmueble',
            'vertical_height_number' => 'total de niveles del inmueble',
            'zip_code' => 'código postal',
            'zip_code_id' => 'colonia/zip_code_id',
        ];
    }

    /**
     * Define error messages.
     */
    public function messages(): array
    {
        return [
            'amenities.*.distinct' => 'Cada elemento en :attribute debe ser único.',
            'amenities.*.exists' => 'Cada elemento en :attribute debe existir en la tabla amenities.',
            'amenities.*.numeric' => 'Cada elemento en :attribute debe ser un número.',
            'amenities.array' => 'El campo :attribute debe ser un arreglo.',
            'average_floor_size.min' => 'El campo :attribute debe ser al menos 1.',
            'average_floor_size.numeric' => 'El campo :attribute debe ser un número.',
            'building_status.required' => 'El campo :attribute es obligatorio.',
            'building_type.numeric' => 'El campo :attribute debe ser un número.',
            'building_type.required' => 'El campo :attribute es obligatorio.',
            'built_to.before' => 'El campo :attribute debe ser una fecha antes de 2054-10-29.',
            'built_to.date_format' => 'El campo :attribute debe tener el formato d/m/Y.',
            'certification.string' => 'El campo :attribute debe ser una cadena de texto.',
            'charging_ports.max' => 'El campo :attribute no debe ser mayor a 1000.',
            'charging_ports.min' => 'El campo :attribute debe ser al menos 0.',
            'charging_ports.numeric' => 'El campo :attribute debe ser un número.',
            'city.required' => 'El campo :attribute es obligatorio.',
            'city.string' => 'El campo :attribute debe ser una cadena de texto.',
            'class.integer' => 'El campo :attribute debe ser un número entero.',
            'company.integer' => 'El campo :attribute debe ser un número entero.',
            'construction_date.min' => 'El campo :attribute debe ser al menos 1900.',
            'construction_date.numeric' => 'El campo :attribute debe ser un número.',
            'description.max' => 'El campo :attribute no debe ser mayor a 450 caracteres.',
            'description.string' => 'El campo :attribute debe ser una cadena de texto.',
            'door_height.numeric' => 'El campo :attribute debe ser un número.',
            'energy.max' => 'El campo :attribute no debe ser mayor a 255 caracteres.',
            'energy.string' => 'El campo :attribute debe ser una cadena de texto.',
            'expansion_up_to.in' => 'El campo :attribute debe ser 0 o 1.',
            'ext_number.max' => 'El campo :attribute no debe ser mayor a 6 caracteres.',
            'ext_number.required_if' => 'El campo :attribute es obligatorio cuando cuando el tipo de spot es local comercial ú oficina.',
            'ext_number.string' => 'El campo :attribute debe ser una cadena de texto.',
            'external_id.max' => 'El campo :attribute no debe ser mayor a 20 caracteres.',
            'external_id.required_with' => 'El campo :attribute es obligatorio cuando external_updated_at está presente.',
            'external_id.string' => 'El campo :attribute debe ser una cadena de texto.',
            'external_updated_at.before' => 'El campo :attribute debe ser una fecha antes de 2024-10-29 17:33.',
            'external_updated_at.date' => 'El campo :attribute debe ser una fecha válida.',
            'external_updated_at.string' => 'El campo :attribute debe ser una cadena de texto.',
            'fire_protection_system.*.numeric' => 'Cada elemento en :attribute debe ser un número.',
            'fire_protection_system.array' => 'El campo :attribute debe ser un arreglo.',
            'floor_level.numeric' => 'El campo :attribute debe ser un número.',
            'floor_level_number.max' => 'El campo :attribute no debe ser mayor a 99.',
            'floor_level_number.min' => 'El campo :attribute debe ser al menos 1.',
            'floor_level_number.numeric' => 'El campo :attribute debe ser un número.',
            'floor_level_number.required_if' => 'El campo :attribute es obligatorio cuando floor_level es 6.',
            'floor_material.max' => 'El campo :attribute no debe ser mayor a 255 caracteres.',
            'floor_material.string' => 'El campo :attribute debe ser una cadena de texto.',
            'front.max' => 'El campo :attribute no debe ser mayor a 1000.',
            'front.min' => 'El campo :attribute debe ser al menos 0.',
            'front.numeric' => 'El campo :attribute debe ser un número.',
            'glove.min' => 'El campo :attribute debe ser al menos 0.',
            'glove.numeric' => 'El campo :attribute debe ser un número.',
            'guarantee.*.distinct' => 'Cada elemento en :attribute debe ser único.',
            'guarantee.*.numeric' => 'Cada elemento en :attribute debe ser un número.',
            'guarantee.array' => 'El campo :attribute debe ser un arreglo.',
            'height.max' => 'El campo :attribute no debe ser mayor a 1000.',
            'height.min' => 'El campo :attribute debe ser al menos 0.',
            'height.numeric' => 'El campo :attribute debe ser un número.',
            'height.required_if' => 'El campo :attribute es obligatorio cuando max_height es mayor que 0.',
            'height_between_floors.min' => 'El campo :attribute debe ser al menos 0.',
            'height_between_floors.numeric' => 'El campo :attribute debe ser un número.',
            'hide_address_data.in' => 'El campo :attribute debe ser 0 o 1.',
            'in_corner.in' => 'El campo :attribute debe ser 0 o 1.',
            'int_number.max' => 'El campo :attribute no debe ser mayor a 6 caracteres.',
            'int_number.string' => 'El campo :attribute debe ser una cadena de texto.',
            'is_complex.in' => 'El campo :attribute debe ser 0 o 1.',
            'is_complex.required' => 'El campo :attribute es obligatorio.',
            'is_exclusive.in' => 'El campo :attribute debe ser 0 o 1.',
            'land_use.max' => 'El campo :attribute no debe ser mayor a 250 caracteres.',
            'land_use.string' => 'El campo :attribute debe ser una cadena de texto.',
            'latitude.between' => 'El campo :attribute debe estar entre -90 y 90.',
            'latitude.numeric' => 'El campo :attribute debe ser un número.',
            'local.max' => 'El campo :attribute no debe ser mayor a 6 caracteres.',
            'local.string' => 'El campo :attribute debe ser una cadena de texto.',
            'longitude.between' => 'El campo :attribute debe estar entre -180 y 180.',
            'longitude.numeric' => 'El campo :attribute debe ser un número.',
            'luminaries.in' => 'El campo :attribute debe ser 0 o 1.',
            'luminary_specs.required_if' => 'El campo :attribute es obligatorio cuando luminaries es mayor que 0.',
            'luminary_type.numeric' => 'El campo :attribute debe ser un número.',
            'max_height.gt' => 'El campo :attribute debe ser mayor que altura.',
            'max_height.required' => 'El campo :attribute es obligatorio.',
            'max_rent_price.max' => 'El campo :attribute no debe ser mayor a 10000000000.',
            'max_rent_price.min' => 'El campo :attribute debe ser al menos 0.',
            'max_rent_price.numeric' => 'El campo :attribute debe ser un número.',
            'max_sale_price.max' => 'El campo :attribute no debe ser mayor a 10000000000.',
            'max_sale_price.min' => 'El campo :attribute debe ser al menos 0.',
            'max_sale_price.numeric' => 'El campo :attribute debe ser un número.',
            'min_area_divisible.min' => 'El campo :attribute debe ser al menos 1.',
            'min_area_divisible.numeric' => 'El campo :attribute debe ser un número.',
            'name.max' => 'El campo :attribute no debe ser mayor a 255 caracteres.',
            'name.required' => 'El campo :attribute es obligatorio.',
            'name.string' => 'El campo :attribute debe ser una cadena de texto.',
            'natural_light.max' => 'El porcentaje de :attribute no debe ser mayor a 100.',
            'natural_light.min' => 'El porcentaje de :attribute debe ser al menos 0.',
            'number_of_elevators.integer' => 'El campo :attribute debe ser un número entero.',
            'number_of_elevators.min' => 'El porcentaje de :attribute debe ser al menos 0.',
            'office_age.integer' => 'El campo :attribute debe ser un número entero.',
            'office_age.min' => 'El campo :attribute debe ser al menos 1.',
            'office_area_percent.integer' => 'El campo :attribute debe ser un número entero.',
            'office_area_percent.min' => 'El campo :attribute debe ser al menos 0.',
            'office_area_percent_modality.numeric' => 'El campo :attribute debe ser un número.',
            'parent_id.integer' => 'El campo :attribute debe ser un número entero.',
            'parking_space_by_area.numeric' => 'El campo :attribute debe ser un número.',
            'parking_spaces.numeric' => 'El campo :attribute debe ser un número.',
            'pdf_description.max' => 'El campo :attribute no debe ser mayor a 450 caracteres.',
            'pdf_description.string' => 'El campo :attribute debe ser una cadena de texto.',
            'photo_urls.*.url' => 'Cada URL en :attribute debe ser una URL válida.',
            'photo_urls.array' => 'El campo :attribute debe ser un arreglo.',
            'photo_urls.max' => 'El campo :attribute no debe tener más de 20 elementos.',
            'photos.*.file.image' => 'El campo file en :attribute debe ser una imagen.',
            'photos.*.file.max' => 'El campo file en :attribute no debe ser mayor a 5120 kilobytes.',
            'photos.*.file.mimes' => 'El campo file en :attribute debe ser un archivo de tipo: jpeg, png, jpg.',
            'photos.*.file.required_with' => 'El campo file es obligatorio cuando type está presente en :attribute.',
            'photos.*.type.integer' => 'El campo type en :attribute debe ser un número entero.',
            'photos.array' => 'El campo :attribute debe ser un arreglo.',
            'photos.max' => 'El campo :attribute no debe tener más de 20 elementos.',
            'photos.required' => 'El campo :attribute es obligatorio.',
            'photos_order.*.distinct' => 'Cada elemento en :attribute debe ser único.',
            'photos_order.*.exists' => 'Cada elemento en :attribute debe existir en la tabla photos.',
            'photos_order.*.integer' => 'Cada elemento en :attribute debe ser un número entero.',
            'photos_order.array' => 'El campo :attribute debe ser un arreglo.',
            'photo_cover.array' => 'El campo :attribute debe ser un arreglo.',
            'photo_cover.required' => 'El campo :attribute es obligatorio.',
            'photo_cover.min' => 'El campo :attribute es obligatorio.',
            'possible_bts.in' => 'El campo :attribute debe ser 0 o 1.',
            'publish.boolean' => 'El campo :attribute debe ser verdadero o falso.',
            'reference.max' => 'El campo :attribute no debe ser mayor a 120 caracteres.',
            'reference.string' => 'El campo :attribute debe ser una cadena de texto.',
            'rent_modality_type.required_if' => 'El campo :attribute es obligatorio.',
            'rent_price.regex' => 'El precio de :attribute solo puede tener hasta 2 decimales',
            'rent_price.required_if' => 'El campo :attribute es obligatorio.',
            'rent_price_area.required_if' => 'El campo :attribute es obligatorio.',
            'roofing_type.numeric' => 'El campo :attribute debe ser un número.',
            'sale_modality_type.required_if' => 'El campo :attribute es obligatorio.',
            'sale_price.regex' => 'El precio de :attribute solo puede tener hasta 2 decimales',
            'sale_price.required_if' => 'El campo :attribute es obligatorio.',
            'sale_price_area.required_if' => 'El campo :attribute es obligatorio.',
            'security_type.*.numeric' => 'Cada elemento en :attribute debe ser un número.',
            'security_type.array' => 'El campo :attribute debe ser un arreglo.',
            'space_between_columns.numeric' => 'El campo :attribute debe ser un número.',
            'spot_condition.numeric' => 'El campo :attribute debe ser un número.',
            'spot_type_id.required' => 'El campo :attribute es obligatorio.',
            'square_space.integer' => 'El campo :attribute debe ser un número entero.',
            'square_space.max' => 'El campo :attribute no debe ser mayor a 99999999.',
            'square_space.min' => 'El campo :attribute debe ser al menos 1.',
            'square_space.required' => 'El campo :attribute es obligatorio.',
            'state.required' => 'El campo :attribute es obligatorio.',
            'state.string' => 'El campo :attribute debe ser una cadena de texto.',
            'street.max' => 'El campo :attribute no debe ser mayor a 240 caracteres.',
            'street.required' => 'El campo :attribute es obligatorio.',
            'street.required_with' => 'El campo :attribute es obligatorio cuando latitude y longitude están presentes.',
            'street.string' => 'El campo :attribute debe ser una cadena de texto.',
            'trademarks.*.distinct' => 'Cada elemento en :attribute debe ser único.',
            'trademarks.*.exists' => 'Cada elemento en :attribute debe existir en la tabla trademarks.',
            'trademarks.*.numeric' => 'Cada elemento en :attribute debe ser un número.',
            'trademarks.array' => 'El campo :attribute debe ser un arreglo.',
            'vehicle_ramp.max' => 'El campo :attribute no debe ser mayor a 99.',
            'vehicle_ramp.min' => 'El campo :attribute debe ser al menos 0.',
            'vehicle_ramp.numeric' => 'El campo :attribute debe ser un número.',
            'vertical_height.numeric' => 'El campo :attribute debe ser un número.',
            'vertical_height_number.max' => 'El campo :attribute no debe ser mayor a 99.',
            'vertical_height_number.min' => 'El campo :attribute debe ser al menos 1.',
            'vertical_height_number.numeric' => 'El campo :attribute debe ser un número.',
            'zip_code.max' => 'El campo :attribute no debe ser mayor a 5 caracteres.',
            'zip_code.numeric' => 'El campo :attribute debe ser un número.',
            'zip_code.required' => 'El campo :attribute es obligatorio.',
            'zip_code_id.exists' => 'El campo :attribute debe existir en la tabla zip_codes.',
            'zip_code_id.numeric' => 'El campo :attribute debe ser un número.',
            'zip_code_id.required' => 'El campo :attribute es obligatorio.',
        ];
    }
}

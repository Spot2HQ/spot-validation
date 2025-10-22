<?php

namespace Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core;

trait TerrainRules
{
    public function terrainRules(): array
    {
        return [
            'land_use' => 'sometimes|nullable|string|max:250',
            'front' => 'sometimes|nullable|numeric|min:0|max:1000',
            'energy' => 'sometimes|nullable|string|max:255',
            'min_area_divisible' => 'sometimes|numeric|min:1',
        ];
    }
}

<?php

namespace Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core;

trait MallRules
{
    public function mallRules(): array
    {
        return [
            'certification' => 'sometimes|nullable|string',
            'glove' => 'sometimes|nullable|numeric|min:0',
        ];
    }
}

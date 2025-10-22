<?php

namespace Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core;

trait RetailRules
{
    public function retailRules(): array
    {
        return [
            'glove' => 'sometimes|nullable|numeric|min:0',
        ];
    }
}

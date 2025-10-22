<?php

namespace Spot2HQ\SpotValidation\Traits;

use Illuminate\Routing\Route;

trait WithPathParameters
{
    // ******************
    //   Don't override
    // ******************

    /**
     * {@inheritDoc}
     */
    public function all($keys = null): ?array
    {
        $route = $this->route();
        if ($route === null) {
            return parent::all();
        }
        
        return array_replace_recursive(
            parent::all(),
            $route->parameters()
        );
    }
}

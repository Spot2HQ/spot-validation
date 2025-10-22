<?php
namespace Spot2HQ\SpotValidation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseSpotRequest extends FormRequest
{
    protected function getPackageConfig(string $key, mixed $default = null): mixed
    {
        return config("spot-validation.{$key}", $default);
    }
}
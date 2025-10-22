<?php

namespace Spot2HQ\SpotValidation\Http\Requests\Rules\Spots;

use Closure;
use Spot2HQ\SpotValidation\Contracts\ExchangeRateProviderInterface;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceCurrencyTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceAreaTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;

class MinPriceByArea implements ValidationRule
{
    private int $minimumPricePerArea;

    private ExchangeRateProviderInterface $exchangeRateProvider;

    public function __construct(
        private readonly float $square_space,
        private readonly int $price_area,
        private readonly int $currency_type,
        private readonly string $label = 'renta',
        ?ExchangeRateProviderInterface $exchangeRateProvider = null
    ) {
        if ($square_space <= 0) {
            throw new InvalidArgumentException('Square space must be a positive number');
        }

        $this->exchangeRateProvider = $exchangeRateProvider ?? app(ExchangeRateProviderInterface::class);
        $this->minimumPricePerArea = config('spot-validation.pricing.minimum_price_per_area', 10000);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value < 0) {
            $fail('El precio no puede ser negativo.');
            return;
        }

        // Convert USD to MXN if needed 
        if ($this->currency_type === PriceCurrencyTypeEnum::USD->value) {
            $value = $this->exchangeRateProvider->convertUsdToMxn($value);
        }

        if ($this->price_area === PriceAreaTypeEnum::PER_SQUARE_METER->value) {
            // For per m2, validate the price per m2 directly
            $minPricePerM2 = $this->minimumPricePerArea / $this->square_space;
            if ($value < $minPricePerM2) {
                $fail('El precio es inferior al precio mínimo por metro cuadrado.');
                return;
            }
        } else {
            // For total area, validate the total price
            if ($value < $this->minimumPricePerArea) {
                $fail('El precio es inferior al precio mínimo total.');
            }
        }
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        $currency = $this->currency_type === PriceCurrencyTypeEnum::USD->value ? 'USD' : 'MXN';
        
        if ($this->currency_type === PriceCurrencyTypeEnum::USD->value) {
            $minPrice = number_format($this->exchangeRateProvider->convertMxnToUsd($this->minimumPricePerArea), 2);
        } else {
            $minPrice = number_format($this->minimumPricePerArea, 2);
        }

        return "El precio de $this->label por área debe ser mayor o igual a \$$minPrice $currency.";
    }
}

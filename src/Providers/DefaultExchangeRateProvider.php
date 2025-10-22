<?php

namespace Spot2HQ\SpotValidation\Providers;

use Spot2HQ\SpotValidation\Contracts\ExchangeRateProviderInterface;

/**
 * Default exchange rate provider using configuration values
 * 
 * This provider uses static configuration values for exchange rates.
 * Projects can override this by binding their own implementation
 * to the ExchangeRateProviderInterface.
 */
class DefaultExchangeRateProvider implements ExchangeRateProviderInterface
{
    private float $exchangeRate;

    public function __construct()
    {
        $this->exchangeRate = config('spot-validation.pricing.default_exchange_rate', 20.00);
    }

    /**
     * Get the current exchange rate from USD to MXN
     * 
     * @return float The exchange rate value
     */
    public function getUsdToMxnRate(): float
    {
        return $this->exchangeRate;
    }

    /**
     * Convert USD amount to MXN using current exchange rate
     * 
     * @param float $usdAmount Amount in USD
     * @return float Amount in MXN
     */
    public function convertUsdToMxn(float $usdAmount): float
    {
        return $usdAmount * $this->exchangeRate;
    }

    /**
     * Convert MXN amount to USD using current exchange rate
     * 
     * @param float $mxnAmount Amount in MXN
     * @return float Amount in USD
     */
    public function convertMxnToUsd(float $mxnAmount): float
    {
        if ($this->exchangeRate === 0.0) {
            return $mxnAmount; // Avoid division by zero
        }
        return $mxnAmount / $this->exchangeRate;
    }
}

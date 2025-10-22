<?php

namespace Spot2HQ\SpotValidation\Contracts;

/**
 * Interface for exchange rate providers
 * 
 * This interface allows different projects to implement their own
 * exchange rate logic while keeping the validation package decoupled.
 */
interface ExchangeRateProviderInterface
{
    /**
     * Get the current exchange rate from USD to MXN
     * 
     * @return float The exchange rate value
     */
    public function getUsdToMxnRate(): float;

    /**
     * Convert USD amount to MXN using current exchange rate
     * 
     * @param float $usdAmount Amount in USD
     * @return float Amount in MXN
     */
    public function convertUsdToMxn(float $usdAmount): float;

    /**
     * Convert MXN amount to USD using current exchange rate
     * 
     * @param float $mxnAmount Amount in MXN
     * @return float Amount in USD
     */
    public function convertMxnToUsd(float $mxnAmount): float;
}

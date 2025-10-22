<?php

namespace Spot2HQ\SpotValidation\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Service Provider for Spot Validation Package
 * 
 * This provider handles the registration and booting of the package,
 * including configuration publishing and custom validation rules.
 * 
 * @package Spot2HQ\SpotValidation\Providers
 */
class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * 
     * This method is called when the service provider is registered.
     * It's used to bind things into the service container.
     *
     * @return void
     */
    public function register(): void
    {
        // Merge package configuration with application configuration
        $this->mergeConfigFrom(
            __DIR__.'/../Config/spot-validation.php',
            'spot-validation'
        );
    }

    /**
     * Bootstrap any application services.
     * 
     * This method is called after all service providers have been registered.
     * It's used to perform actions after all providers are registered.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish configuration file
        $this->publishes([
            __DIR__.'/../Config/spot-validation.php' => config_path('spot-validation.php'),
        ], 'spot-validation-config');


        // Register custom validation rules
        $this->registerCustomValidationRules();
    }


    /**
     * Register custom validation rules.
     * 
     * This method can be used to register any custom validation rules
     * that are specific to the spot validation package.
     *
     * @return void
     */
    protected function registerCustomValidationRules(): void
    {
        // Register exchange rate provider binding
        $this->app->bind(
            \Spot2HQ\SpotValidation\Contracts\ExchangeRateProviderInterface::class,
            \Spot2HQ\SpotValidation\Providers\DefaultExchangeRateProvider::class
        );
        // 
        // Validator::extend('spot_type', function ($attribute, $value, $parameters, $validator) {
        //     return SpotTypeEnum::tryFrom($value) !== null;
        // });
        //
        // Validator::replacer('spot_type', function ($message, $attribute, $rule, $parameters) {
        //     return str_replace(':attribute', $attribute, 'The :attribute must be a valid spot type.');
        // });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            'spot-validation',
        ];
    }
}


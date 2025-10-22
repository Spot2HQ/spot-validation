<?php

namespace Spot2HQ\SpotValidation\Tests\Helpers;

use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\IndustrialRules;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\SharedRules;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\CorporateRules;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\MallRules;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\OfficeRules;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\RetailRules;
use Spot2HQ\SpotValidation\Http\Requests\Rules\Spots\Core\TerrainRules;

/**
 * Helper class to extract validation rules from traits for testing
 */
class ValidationRuleExtractor
{
    /**
     * Unified mock input data for all traits
     * This ensures consistent behavior across all trait tests
     */
    public static function getMockInputData($key = null, $default = null): mixed
    {
        // Comprehensive mock data that covers all trait needs
        $data = [
            // SharedRules needs
            'square_space' => 1000,
            'rent_price_area' => 1, // TOTAL
            'currency_type' => 1,   // MXN
            'sale_price_area' => 1, // TOTAL
            
            // IndustrialRules needs  
            'office_area_percent_modality' => 1, // PERCENTAGE
            
            // Additional common fields
            'modality_type' => 1, // RENT
            'rent_price' => 15000,
            'sale_price' => 200000,
        ];
        
        return $data[$key] ?? $default;
    }
    /**
     * Extract rules from IndustrialRules trait
     */
    public static function getIndustrialRules(): array
    {
        $mock = new class {
            use IndustrialRules;
            
            public function input($key = null, $default = null)
            {
                return ValidationRuleExtractor::getMockInputData($key, $default);
            }
        };
        
        return $mock->industrialRules();
    }

    /**
     * Extract rules from SharedRules trait
     */
    public static function getSharedRules(): array
    {
        $mock = new class {
            use SharedRules;
            
            public function input($key = null, $default = null)
            {
                return ValidationRuleExtractor::getMockInputData($key, $default);
            }
        };
        
        return $mock->sharedRules();
    }

    /**
     * Extract price rules from SharedRules trait (MXN currency)
     */
    public static function getPriceRules(): array
    {
        $mock = new class {
            use SharedRules;
            
            public function input($key = null, $default = null)
            {
                return ValidationRuleExtractor::getMockInputData($key, $default);
            }
        };
        
        return $mock->priceRules();
    }

    /**
     * Extract price rules from SharedRules trait with USD currency
     */
    public static function getPriceRulesWithUsd(): array
    {
        $mock = new class {
            use SharedRules;
            
            public function input($key = null, $default = null)
            {
                return ValidationRuleExtractor::getMockInputDataWithUsd($key, $default);
            }
        };
        
        return $mock->priceRules();
    }

    /**
     * Mock input data specifically for USD currency testing
     */
    public static function getMockInputDataWithUsd($key = null, $default = null): mixed
    {
        $data = [
            // SharedRules needs with USD currency
            'square_space' => 1000,
            'rent_price_area' => 1, // TOTAL
            'currency_type' => 2,   // USD
            'sale_price_area' => 1, // TOTAL
            
            // Additional common fields for USD
            'modality_type' => 1, // RENT
            'rent_price' => 750,  // 750 USD * 20 = 15,000 MXN (above minimum)
            'sale_price' => 10000, // 10,000 USD * 20 = 200,000 MXN
        ];
        
        return $data[$key] ?? $default;
    }

    /**
     * Extract contact rules from SharedRules trait
     */
    public static function getContactRules(): array
    {
        $mock = new class {
            use SharedRules;
        };
        
        return $mock->contactRules();
    }

    /**
     * Extract detail rules from SharedRules trait
     */
    public static function getDetailRules(): array
    {
        $mock = new class {
            use SharedRules;
        };
        
        return $mock->detailRules();
    }

    /**
     * Extract rules from CorporateRules trait
     */
    public static function getCorporateRules(): array
    {
        $mock = new class {
            use CorporateRules;
            
            public function input($key = null, $default = null)
            {
                return ValidationRuleExtractor::getMockInputData($key, $default);
            }
        };
        
        return $mock->corporateRules();
    }

    /**
     * Extract rules from MallRules trait
     */
    public static function getMallRules(): array
    {
        $mock = new class {
            use MallRules;
            
            public function input($key = null, $default = null)
            {
                return ValidationRuleExtractor::getMockInputData($key, $default);
            }
        };
        
        return $mock->mallRules();
    }

    /**
     * Extract rules from OfficeRules trait
     */
    public static function getOfficeRules(): array
    {
        $mock = new class {
            use OfficeRules;
            
            public function input($key = null, $default = null)
            {
                return ValidationRuleExtractor::getMockInputData($key, $default);
            }
        };
        
        return $mock->officeRules();
    }

    /**
     * Extract rules from RetailRules trait
     */
    public static function getRetailRules(): array
    {
        $mock = new class {
            use RetailRules;
            
            public function input($key = null, $default = null)
            {
                return ValidationRuleExtractor::getMockInputData($key, $default);
            }
        };
        
        return $mock->retailRules();
    }

    /**
     * Extract rules from TerrainRules trait
     */
    public static function getTerrainRules(): array
    {
        $mock = new class {
            use TerrainRules;
            
            public function input($key = null, $default = null)
            {
                return ValidationRuleExtractor::getMockInputData($key, $default);
            }
        };
        
        return $mock->terrainRules();
    }

    /**
     * Get simplified rules for package testing (removes database dependencies)
     */
    public static function getSimplifiedRules(array $rules): array
    {
        $simplified = [];
        
        foreach ($rules as $field => $rule) {
            // Skip database-dependent rules
            if (is_string($rule) && str_contains($rule, 'exists:')) {
                continue;
            }
            
            if (is_array($rule)) {
                $simplifiedRule = [];
                foreach ($rule as $r) {
                    if (is_string($r) && str_contains($r, 'exists:')) {
                        continue;
                    }
                    $simplifiedRule[] = $r;
                }
                if (!empty($simplifiedRule)) {
                    $simplified[$field] = $simplifiedRule;
                }
            } else {
                $simplified[$field] = $rule;
            }
        }
        
        return $simplified;
    }

    /**
     * Extract specific field rules from a trait
     */
    public static function getFieldRules(string $traitClass, string $method, string $field): array
    {
        $rules = self::getRulesFromTrait($traitClass, $method);
        
        return $rules[$field] ?? [];
    }

    /**
     * Generic method to extract rules from any trait
     */
    private static function getRulesFromTrait(string $traitClass, string $method): array
    {
        $mock = new class {
            use IndustrialRules, SharedRules, CorporateRules, MallRules, OfficeRules, RetailRules, TerrainRules;
            
            public function input($key = null, $default = null)
            {
                return ValidationRuleExtractor::getMockInputData($key, $default);
            }
        };
        
        return $mock->$method();
    }
}

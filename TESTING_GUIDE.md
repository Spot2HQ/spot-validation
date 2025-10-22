# Testing Guide for Spot Validation Package

## Running Tests Without Database or .env Configuration

This package is designed to be **database-independent** and **environment-independent** for testing. You should be able to run all tests without setting up a database or `.env` file. The package uses **PascalCase namespaces** (`Spot2\SpotValidation\`) following PSR-4 conventions.

## Quick Test Commands

### Run All Tests
```bash
./vendor/bin/phpunit
```

### Run Only Unit Tests (No Database Required)
```bash
./vendor/bin/phpunit tests/Unit
```

### Run Only MinPriceByArea Tests
```bash
./vendor/bin/phpunit tests/Unit/Rules/MinPriceByAreaTest.php
```

### Run Rule-Specific Tests
```bash
# Corporate validation
./vendor/bin/phpunit tests/Feature/Requests/Rules/CorporateValidationTest.php

# Industrial validation
./vendor/bin/phpunit tests/Feature/Requests/Rules/IndustrialValidationTest.php

# Office validation
./vendor/bin/phpunit tests/Feature/Requests/Rules/OfficeValidationTest.php
```

### Run with Verbose Output
```bash
./vendor/bin/phpunit --verbose
```

## Test Categories

### ✅ **Unit Tests** (No Database Required)
- `tests/Unit/Rules/MinPriceByAreaTest.php` - Core validation logic
- `tests/Unit/Providers/DefaultExchangeRateProviderTest.php` - Exchange rate provider
- `tests/Unit/Enums/` - All enum tests (SpotTypeEnum, PriceEnums, SpotCatalogEnums, EnumHelper)
- `tests/Unit/Traits/WithPathParametersTest.php` - Trait functionality

### ✅ **Feature Tests** (Package-Friendly, No Database Required)
- `tests/Feature/Requests/Rules/` - Rule-specific validation tests:
  - `CorporateValidationTest.php` - Corporate spot validation
  - `IndustrialValidationTest.php` - Industrial spot validation
  - `MallValidationTest.php` - Mall spot validation
  - `OfficeValidationTest.php` - Office spot validation
  - `RetailValidationTest.php` - Retail spot validation
  - `TerrainValidationTest.php` - Terrain spot validation
- `tests/Feature/Requests/MinPriceByAreaSharedRulesTest.php` - Integration with SharedRules
- `tests/Feature/Requests/SpotRequestValidationTest.php` - General validation tests
- `tests/Feature/Providers/` - Service provider tests:
  - `ExchangeRateProviderIntegrationTest.php` - Service provider integration
  - `ServiceProviderTest.php` - Service provider registration

## Configuration for Testing

The package automatically sets up test configuration in `TestCase.php`:

```php
// Automatically configured for testing
'spot-validation' => [
    'pricing' => [
        'default_exchange_rate' => 20.00,
        'minimum_price_per_area' => 10000,
    ],
    // ... other config
],
'photos' => [
    'types' => [1 => 'Normal', 2 => 'Mapa', ...],
    // ... other config
],
'prices' => [
    'USD_CURRENCY_TYPE' => 2,
    'AREA_TYPE_M2' => 2,
],
```

## Troubleshooting

### If Tests Fail with Database Errors

1. **Run only Unit tests first:**
   ```bash
   ./vendor/bin/phpunit tests/Unit
   ```

2. **Check if it's a configuration issue:**
   ```bash
   ./vendor/bin/phpunit tests/Unit/Rules/MinPriceByAreaSimpleTest.php
   ```

### If Tests Fail with Missing Configuration

The package should handle missing configuration gracefully. If you see errors about missing config:

1. **Check TestCase.php** - Make sure `getEnvironmentSetUp()` is properly configured
2. **Run with debug output:**
   ```bash
   ./vendor/bin/phpunit --verbose --debug
   ```

### If Tests Fail with Service Provider Issues

1. **Check if ValidationServiceProvider is registered:**
   ```bash
   ./vendor/bin/phpunit tests/Feature/Providers/ExchangeRateProviderIntegrationTest.php
   ```

## Test Structure

```
tests/
├── Unit/                          # ✅ No database required
│   ├── Rules/
│   │   └── MinPriceByAreaTest.php
│   ├── Providers/
│   │   └── DefaultExchangeRateProviderTest.php
│   ├── Enums/
│   │   ├── SpotTypeEnumTest.php
│   │   ├── PriceEnumsTest.php
│   │   ├── SpotCatalogEnumsTest.php
│   │   └── EnumHelperTest.php
│   └── Traits/
│       └── WithPathParametersTest.php
├── Feature/                       # ✅ Package-friendly, no database required
│   ├── Requests/
│   │   ├── Rules/
│   │   │   ├── CorporateValidationTest.php
│   │   │   ├── IndustrialValidationTest.php
│   │   │   ├── MallValidationTest.php
│   │   │   ├── OfficeValidationTest.php
│   │   │   ├── RetailValidationTest.php
│   │   │   └── TerrainValidationTest.php
│   │   ├── MinPriceByAreaSharedRulesTest.php
│   │   └── SpotRequestValidationTest.php
│   └── Providers/
│       ├── ExchangeRateProviderIntegrationTest.php
│       └── ServiceProviderTest.php
├── Helpers/
│   └── ValidationRuleExtractor.php
└── TestCase.php                   # Base test class with configuration
```

## What Each Test Covers

### MinPriceByAreaTest.php
- ✅ Valid/invalid MXN prices
- ✅ Valid/invalid USD prices  
- ✅ Per m2 vs total area calculations
- ✅ Negative price validation
- ✅ Custom exchange rate providers
- ✅ Configuration-based minimum prices
- ✅ Error message generation

### DefaultExchangeRateProviderTest.php
- ✅ Exchange rate calculations
- ✅ USD to MXN conversion
- ✅ MXN to USD conversion
- ✅ Configuration-based rates
- ✅ Edge cases (zero, decimals, large numbers)

### Rule-Specific Validation Tests
- ✅ **CorporateValidationTest.php** - Average floor size, certification, building class validation
- ✅ **IndustrialValidationTest.php** - Building type, office area, luminary type, height validation
- ✅ **MallValidationTest.php** - Certification, glove value validation
- ✅ **OfficeValidationTest.php** - Fire protection, security, elevator validation
- ✅ **RetailValidationTest.php** - Glove value validation
- ✅ **TerrainValidationTest.php** - Land use, front, energy, area validation

### ExchangeRateProviderIntegrationTest.php
- ✅ Service provider registration
- ✅ Custom provider binding
- ✅ Laravel container integration
- ✅ Different spot types
- ✅ Error handling

### MinPriceByAreaSharedRulesTest.php
- ✅ Integration with SharedRules trait
- ✅ USD and MXN currency validation
- ✅ Custom exchange rate providers
- ✅ Price modality validation
- ✅ Real validation rule extraction

## Expected Test Results

When running `./vendor/bin/phpunit`, you should see:

```
PHPUnit 12.3.15 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.24
Configuration: /path/to/spot-validation/phpunit.xml

...............................................................  63 / 172 ( 36%)
............................................................... 126 / 172 ( 73%)
..............................................                  172 / 172 (100%)

Time: 00:00.706, Memory: 38.50 MB

OK (172 tests, 409 assertions)
```

## Package Testing Philosophy

This package follows the **"Package First"** testing philosophy:

1. **No External Dependencies**: Tests don't require databases, Redis, or external services
2. **Configuration-Driven**: All configuration is set up in test setup methods
3. **Mock-Friendly**: Uses interfaces and dependency injection for easy mocking
4. **Isolated**: Each test is independent and can run in any order
5. **Fast**: Unit tests should run in milliseconds, not seconds

## For Package Users

When you use this package in your project, you can:

1. **Run package tests** to verify it works in your environment
2. **Override configuration** in your test setup
3. **Mock exchange rate providers** for your specific use case
4. **Extend test cases** for your specific validation needs

The package is designed to be **testable** and **reliable** across different environments without requiring complex setup.

## Key Features

### ✅ Partial Validation Testing
- Uses `ValidationRuleExtractor` helper to extract real validation rules from traits
- Tests use actual validation logic, not hardcoded rules
- Tests automatically stay in sync with rule changes
- Comprehensive coverage of all rule traits

### ✅ Enum-Based Testing
- All tests use proper enum constants (e.g., `FireProtectionSystemEnum::SPRINKLERS`)
- No hardcoded values in tests
- Type-safe validation testing
- Proper namespace usage (`Spot2\SpotValidation\`)

### ✅ Package-Friendly Design
- No database dependencies
- No external service requirements
- Self-contained test configuration
- Fast execution (< 1 second for full suite)

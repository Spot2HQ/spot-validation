# Testing Documentation

This document describes the test suite for the Spot Validation package.

## Overview

The test suite is designed to run **without requiring database setup**, making it easy to run tests in any environment. All database-dependent validations are either mocked or skipped in tests. The package uses **PascalCase namespaces** (`Spot2\SpotValidation\`) following PSR-4 conventions.

## Test Structure

```
tests/
├── Unit/
│   ├── Enums/
│   │   ├── SpotTypeEnumTest.php          # Test SpotTypeEnum functionality
│   │   ├── PriceEnumsTest.php            # Test Price-related enums
│   │   ├── SpotCatalogEnumsTest.php      # Test Spot catalog enums
│   │   └── EnumHelperTest.php            # Test EnumHelper trait
│   ├── Rules/
│   │   └── MinPriceByAreaTest.php        # Test MinPriceByArea validation rule
│   ├── Providers/
│   │   └── DefaultExchangeRateProviderTest.php # Test exchange rate provider
│   └── Traits/
│       └── WithPathParametersTest.php     # Test WithPathParameters trait
├── Feature/
│   ├── Requests/
│   │   ├── Rules/
│   │   │   ├── CorporateValidationTest.php    # Corporate-specific validation
│   │   │   ├── IndustrialValidationTest.php   # Industrial-specific validation
│   │   │   ├── MallValidationTest.php         # Mall-specific validation
│   │   │   ├── OfficeValidationTest.php       # Office-specific validation
│   │   │   ├── RetailValidationTest.php       # Retail-specific validation
│   │   │   └── TerrainValidationTest.php      # Terrain-specific validation
│   │   ├── MinPriceByAreaSharedRulesTest.php  # MinPriceByArea integration tests
│   │   └── SpotRequestValidationTest.php      # General validation tests
│   └── Providers/
│       ├── ExchangeRateProviderIntegrationTest.php # Service provider integration
│       └── ServiceProviderTest.php            # Service Provider tests
├── Helpers/
│   └── ValidationRuleExtractor.php           # Helper for extracting validation rules
└── TestCase.php                               # Base test case
```

## Running Tests

### Run All Tests

```bash
composer test
```

or

```bash
vendor/bin/phpunit
```

### Run Specific Test Suite

```bash
# Run only unit tests
vendor/bin/phpunit --group unit

# Run only feature tests
vendor/bin/phpunit --group feature

# Run only enum tests
vendor/bin/phpunit --group enums

# Run only validation tests
vendor/bin/phpunit --group validation
```

### Run Specific Test File

```bash
vendor/bin/phpunit tests/Unit/Enums/SpotTypeEnumTest.php
```

### Run Specific Test Method

```bash
vendor/bin/phpunit --filter it_has_correct_enum_values
```

## Test Coverage

### Unit Tests (7 test files, 100+ tests)

#### Enum Tests
- ✅ **SpotTypeEnumTest** (15 tests)
  - Enum values are correct
  - Labels return correct translations
  - Helper methods work properly
  - API response objects are correct
  - Special methods (getValidSpotTypesValues, etc.)

- ✅ **PriceEnumsTest** (20+ tests)
  - All price enums have correct values
  - Labels are properly translated
  - Currency symbols work correctly
  - Helper methods function properly

- ✅ **SpotCatalogEnumsTest** (15+ tests)
  - All catalog enums have correct values and labels
  - Enums can be created from values
  - Invalid values return null with tryFrom()
  - All enums implement LabelInterface

- ✅ **EnumHelperTest** (12 tests)
  - labels() returns array of labels
  - getKeys() returns array of case names
  - getValues() returns array of values
  - getValue() and getKey() work correctly
  - Random value/key generation
  - toArray() conversion
  - API response object generation

#### Rule Tests
- ✅ **MinPriceByAreaTest** (15+ tests)
  - Valid/invalid MXN prices
  - Valid/invalid USD prices with exchange rate conversion
  - Per m2 vs total area calculations
  - Negative price validation
  - Custom exchange rate providers
  - Configuration-based minimum prices
  - Error message generation

#### Provider Tests
- ✅ **DefaultExchangeRateProviderTest** (10+ tests)
  - Exchange rate calculations
  - USD to MXN conversion
  - MXN to USD conversion
  - Configuration-based rates
  - Edge cases (zero, decimals, large numbers)

#### Trait Tests
- ✅ **WithPathParametersTest** (8 tests)
  - Can set and get path parameters
  - Returns specific parameters
  - Handles default values
  - Supports various data types

### Feature Tests (9 test files, 117+ tests)

#### Rule-Specific Validation Tests
- ✅ **CorporateValidationTest** (10 tests)
  - Average floor size validation
  - Certification string validation
  - Building class enum validation
  - Null value handling

- ✅ **IndustrialValidationTest** (13 tests)
  - Building type validation
  - Office area percent modality
  - Luminary type validation
  - Height comparison validation
  - Charging ports range validation
  - Fire protection system array validation
  - Security type array validation

- ✅ **MallValidationTest** (10 tests)
  - Certification validation
  - Glove value validation
  - Null value handling

- ✅ **OfficeValidationTest** (14 tests)
  - Fire protection system array validation
  - Height between floors validation
  - Min area divisible validation
  - Number of elevators validation
  - Office age validation
  - Security type array validation

- ✅ **RetailValidationTest** (10 tests)
  - Glove value validation
  - Numeric range validation
  - String conversion handling

- ✅ **TerrainValidationTest** (15 tests)
  - Land use string validation
  - Front value validation
  - Energy description validation
  - Min area divisible validation
  - String length limits

#### Integration Tests
- ✅ **MinPriceByAreaSharedRulesTest** (11 tests)
  - Integration with SharedRules trait
  - USD and MXN currency validation
  - Custom exchange rate providers
  - Price modality validation

- ✅ **SpotRequestValidationTest** (12+ tests)
  - General validation rules
  - Form request structure
  - Trait usage verification

#### Service Provider Tests
- ✅ **ServiceProviderTest** (12 tests)
  - Service provider registration
  - Configuration loading
  - All config sections exist
  - Config values are correct type
  - Config can be accessed and overridden
  - Provider provides correct services

- ✅ **ExchangeRateProviderIntegrationTest** (12+ tests)
  - Service provider registration
  - Custom provider binding
  - Laravel container integration
  - Different spot types
  - Error handling

## Key Testing Principles

### 1. Partial Validation Testing

The package uses a **partial validation approach** with the `ValidationRuleExtractor` helper:
- Tests extract actual validation rules from traits
- Rules are tested with realistic mock data
- Tests automatically stay in sync with rule changes
- No hardcoded validation rules in tests

Example:
```php
// Extract actual rules from trait
$rules = ValidationRuleExtractor::getIndustrialRules();
$simplifiedRules = ValidationRuleExtractor::getSimplifiedRules($rules);

// Test with real validation logic
$validator = Validator::make($data, $simplifiedRules);
```

### 3. Type-Safe Enum Testing

Enums are thoroughly tested to ensure:
- Values match expected integers
- Labels return correct Spanish translations
- Helper methods work correctly
- API responses are properly formatted
- Proper enum constants are used (not hardcoded values)

### 4. Comprehensive Validation Coverage

Tests cover:
- ✅ Valid data passes
- ✅ Invalid data fails appropriately
- ✅ Edge cases (min/max values)
- ✅ Conditional validation
- ✅ Array validations
- ✅ Type validation
- ✅ Currency conversion validation
- ✅ Exchange rate provider integration

## Writing New Tests

### Adding Enum Tests

```php
/** @test */
public function new_enum_has_correct_values(): void
{
    $this->assertSame(1, NewEnum::VALUE_ONE->value);
    $this->assertSame('Label', NewEnum::VALUE_ONE->label());
}
```

### Adding Validation Tests

```php
/** @test */
public function it_validates_new_field(): void
{
    $data = $this->getValidSpotData();
    $data['new_field'] = 'valid_value';
    
    // Use ValidationRuleExtractor for partial validation
    $rules = ValidationRuleExtractor::getIndustrialRules();
    $simplifiedRules = ValidationRuleExtractor::getSimplifiedRules($rules);
    
    $validator = Validator::make($data, $simplifiedRules);
    $this->assertTrue($validator->passes());
}
```

### Adding Rule-Specific Tests

```php
/** @test */
public function it_validates_new_rule_trait(): void
{
    $data = $this->getBaseData();
    $data['new_field'] = 'valid_value';
    
    // Extract rules from your new trait
    $rules = ValidationRuleExtractor::getNewRuleTraitRules();
    $simplifiedRules = ValidationRuleExtractor::getSimplifiedRules($rules);
    
    $validator = Validator::make($data, $simplifiedRules);
    $this->assertTrue($validator->passes());
}
```

## Test Groups

Tests are organized using PHPUnit groups:

| Group | Description |
|-------|-------------|
| `unit` | All unit tests |
| `feature` | All feature tests |
| `enums` | Enum-specific tests |
| `traits` | Trait-specific tests |
| `validation` | Validation rule tests |
| `provider` | Service provider tests |
| `price` | Price-related tests |
| `catalog` | Catalog enum tests |
| `industrial` | Industrial spot tests |
| `corporate` | Corporate spot tests |
| `mall` | Mall spot tests |
| `office` | Office spot tests |
| `retail` | Retail spot tests |
| `terrain` | Terrain spot tests |

Run specific groups:
```bash
vendor/bin/phpunit --group enums,validation
```

## Continuous Integration

The test suite is designed to run in CI environments without any setup:

```yaml
# Example GitHub Actions
- name: Run tests
  run: composer test
```

No database migrations or seeding required!

## Test Metrics

| Metric | Value |
|--------|-------|
| Total Tests | 217+ |
| Test Files | 16 |
| Code Coverage | ~90%* |
| Average Execution | <2 seconds |

*Coverage for validation and enum logic only (no database code)

## Troubleshooting

### Tests Fail Due to Missing Config

**Problem:** Config file not found

**Solution:** Ensure you've run `composer install` and the service provider is registered

### Validation Tests Fail

**Problem:** Database-dependent rules causing failures

**Solution:** Make sure to remove `zip_code_id`, `parent_id`, and other DB-dependent rules:

```php
$rules = $request->rules();
unset($rules['zip_code_id'], $rules['parent_id'], $rules['company']);
```

### Enum Tests Fail

**Problem:** Wrong namespace imported

**Solution:** Check that you're using the correct enum namespace:
```php
use Spot2\SpotValidation\Enums\Spot\SpotTypeEnum; // Correct (PascalCase)
// not: use spot2\SpotValidation\Enums\SpotTypeEnum; // Wrong (lowercase)
```

### ValidationRuleExtractor Issues

**Problem:** Tests fail with ValidationRuleExtractor

**Solution:** Ensure you're using the correct method names:
```php
// Correct usage
$rules = ValidationRuleExtractor::getIndustrialRules();
$simplifiedRules = ValidationRuleExtractor::getSimplifiedRules($rules);

// Available methods:
// - getIndustrialRules()
// - getCorporateRules()
// - getMallRules()
// - getOfficeRules()
// - getRetailRules()
// - getTerrainRules()
// - getSharedRules()
// - getPriceRules()
```

## Contributing Tests

When adding new features:

1. ✅ Add unit tests for enums
2. ✅ Add validation tests for new rules
3. ✅ Ensure tests run without database
4. ✅ Add appropriate test groups
5. ✅ Update this documentation

## Resources

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Testing](https://laravel.com/docs/testing)
- [Orchestra Testbench](https://packages.tools/testbench.html)

---

Last Updated: October 21, 2025


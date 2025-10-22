# Spot Validation Package

A Laravel validation package for real estate spot management, providing comprehensive validation rules, enums, and form requests for creating and updating spot records.

## Features

- ✅ **Type-safe Enums** - All catalogs (building types, price types, etc.) are implemented as PHP 8.3+ backed enums
- ✅ **Modular Validation Rules** - Organized by spot type (Industrial, Office, Corporate, Mall, Retail, Terrain)
- ✅ **Form Request Classes** - Ready-to-use Laravel Form Request classes
- ✅ **Configurable** - Publishable configuration for customization
- ✅ **Auto-discovery** - Automatic Laravel package discovery

## Requirements

- PHP ^8.3
- Laravel ^11.0
- Illuminate Support ^11.0
- Illuminate Validation ^11.0
- Illuminate HTTP ^11.0

## Installation

Install the package via Composer:

```bash
composer require Spot2HQ/spot-validation
```

### Publish Configuration (Optional)

Publish the configuration file to customize validation settings:

```bash
php artisan vendor:publish --tag=spot-validation-config
```

This will create a `config/spot-validation.php` file where you can customize:
- Photo upload limits
- Validation settings
- Pricing configuration
- Feature flags
- Custom validation messages

## Usage

### Using Form Requests

The package provides a `SpotRequest` form request class that handles all validation:

```php
use Spot2HQ\SpotValidation\Http\Requests\SpotRequest;

class SpotController extends Controller
{
    public function store(SpotRequest $request)
    {
        // The request is already validated
        $validated = $request->validated();
        
        // Create your spot...
    }
    
    public function update(SpotRequest $request, $id)
    {
        // The request is already validated
        $validated = $request->validated();
        
        // Update your spot...
    }
}
```

### Using Enums

All catalogs are available as type-safe enums:

```php
use Spot2HQ\SpotValidation\Enums\Spot\BuildingTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\SpotStateEnum;

// Get all enum values
$buildingTypes = BuildingTypeEnum::getValues(); // [1, 2, 3, 4]

// Get enum labels
$labels = BuildingTypeEnum::labels(); 
// ['STEEL_AND_CONCRETE' => 'Acero y concreto', ...]

// Get a specific enum
$priceType = PriceTypeEnum::RENT;
echo $priceType->value; // 1
echo $priceType->label(); // 'Renta'

// Use in validation
Rule::in(BuildingTypeEnum::getValues())
```

### Available Enums

#### Spot Enums
- `BuildingTypeEnum` - Building construction types
- `BuildingStatusEnum` - Construction status
- `BuildingClassEnum` - Building classification (A+, A, B, C)
- `BuildingConditionEnum` - Interior conditions
- `FireProtectionSystemEnum` - Fire protection systems
- `FloorLevelEnum` - Floor levels
- `GuaranteeEnum` - Guarantee types
- `LuminaryTypeEnum` - Lighting types
- `OfficeAreaPercentageModalityEnum` - Office area calculation
- `OfficeVerticalHeightEnum` - Office building heights
- `RoofingTypeEnum` - Roofing materials
- `SecurityTypeEnum` - Security systems
- `SpaceBetweenColumnsEnum` - Column spacing ranges
- `SpotStateEnum` - Publication states
- `SpotTypeEnum` - Spot types

#### Price Enums
- `PriceTypeEnum` - Price modality (Rent, Sale, Both)
- `PriceCurrencyTypeEnum` - Currency types (MXN, USD)
- `PriceAreaTypeEnum` - Area calculation types
- `PriceModalityEnum` - Payment frequency
- `PriceMaintenanceTypeEnum` - Maintenance fee types

### Using Validation Traits

You can also use individual validation rule traits in your own form requests:

```php
use Illuminate\Foundation\Http\FormRequest;
use Spot2HQ\SpotValidation\Http\Requests\Rules\SharedRules;
use Spot2HQ\SpotValidation\Http\Requests\Rules\IndustrialRules;

class CustomSpotRequest extends FormRequest
{
    use SharedRules;
    use IndustrialRules;
    
    public function rules(): array
    {
        return array_merge(
            $this->sharedRules(),
            $this->priceRules(),
            $this->industrialRules(),
        );
    }
}
```

Available rule traits:
- `SharedRules` - Common validation rules for all spot types
- `IndustrialRules` - Industrial property specific rules
- `CorporateRules` - Corporate property specific rules
- `OfficeRules` - Office property specific rules
- `MallRules` - Mall property specific rules
- `RetailRules` - Retail property specific rules
- `TerrainRules` - Terrain/Land property specific rules

## Configuration

After publishing the configuration file, you can customize:

```php
// config/spot-validation.php

return [
    'photos' => [
        'max_count' => 20,
        'max_size' => 5120, // KB
    ],
    
    'validation' => [
        'description_max_length' => 450,
        'max_price' => 10000000000,
    ],
    
    'pricing' => [
        'default_exchange_rate' => 20.00, // USD to MXN
        'minimum_price_per_area' => 10000, // Minimum price per area in MXN
    ],
    
    'features' => [
        'strict_validation' => false,
    ],
];
```

### Exchange Rate Integration

The package includes flexible exchange rate handling for price validation. By default, it uses configuration values, but you can integrate with your own exchange rate database or API.

**For detailed integration instructions, see [Exchange Rate Integration Guide](EXCHANGE_RATE_INTEGRATION.md)**

Quick example for custom exchange rate provider:

```php
// app/Providers/AppServiceProvider.php
use Spot2HQ\SpotValidation\Contracts\ExchangeRateProviderInterface;
use App\Services\CustomExchangeRateProvider;

public function register(): void
{
    $this->app->bind(
        ExchangeRateProviderInterface::class,
        CustomExchangeRateProvider::class
    );
}
```

## Enum Helper Methods

All enums include the following helper methods via the `EnumHelper` trait:

```php
// Get all case names
BuildingTypeEnum::getKeys();
// ['STEEL_AND_CONCRETE', 'BLOCK_AND_SHEET', 'STEEL_BLOCK_AND_SHEET', 'SHEET']

// Get all case values
BuildingTypeEnum::getValues();
// [1, 2, 3, 4]

// Get value by key
BuildingTypeEnum::getValue('STEEL_AND_CONCRETE'); // 1

// Get key by value
BuildingTypeEnum::getKey(1); // 'STEEL_AND_CONCRETE'

// Get random value
BuildingTypeEnum::getRandomValue();

// Get random key
BuildingTypeEnum::getRandomKey();

// Convert to associative array
BuildingTypeEnum::toArray();
// ['STEEL_AND_CONCRETE' => 1, 'BLOCK_AND_SHEET' => 2, ...]

// Get labels (requires LabelInterface)
BuildingTypeEnum::labels();
// ['STEEL_AND_CONCRETE' => 'Acero y concreto', ...]

// Get API response object
$enum = BuildingTypeEnum::STEEL_AND_CONCRETE;
$enum->getApiResponseObject();
// ['id' => 1, 'label' => 'Acero y concreto']
```

## Development

### Running Tests

```bash
composer test
```

### Code Formatting

```bash
composer format
```

### Check Code Style

```bash
composer format-test
```

## License

This package is proprietary software developed by Spot2HQ.

## Support

For issues, questions, or contributions, please contact the development team.


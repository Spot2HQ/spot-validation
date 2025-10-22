# Spot Validation Package - Usage Examples

This document provides practical examples of how to use the Spot Validation package in your Laravel application.

## Table of Contents
1. [Basic Form Request Usage](#basic-form-request-usage)
2. [Working with Enums](#working-with-enums)
3. [Custom Form Requests](#custom-form-requests)
4. [API Responses with Enums](#api-responses-with-enums)
5. [Validation in Controllers](#validation-in-controllers)

---

## Basic Form Request Usage

### Example 1: Creating a New Spot

```php
namespace App\Http\Controllers;

use spot2\SpotValidation\Http\Requests\SpotRequest;
use App\Models\Spot;

class SpotController extends Controller
{
    /**
     * Store a newly created spot in storage.
     */
    public function store(SpotRequest $request)
    {
        // All data is already validated by SpotRequest
        $validated = $request->validated();
        
        $spot = Spot::create($validated);
        
        return response()->json([
            'message' => 'Spot created successfully',
            'data' => $spot
        ], 201);
    }
}
```

### Example 2: Updating an Existing Spot

```php
public function update(SpotRequest $request, Spot $spot)
{
    $validated = $request->validated();
    
    $spot->update($validated);
    
    return response()->json([
        'message' => 'Spot updated successfully',
        'data' => $spot
    ]);
}
```

---

## Working with Enums

### Example 3: Using Enums in Models

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use spot2\SpotValidation\Enums\Spot\SpotTypeEnum;
use spot2\SpotValidation\Enums\Spot\SpotStateEnum;
use spot2\SpotValidation\Enums\Spot\BuildingTypeEnum;

class Spot extends Model
{
    protected $casts = [
        'spot_type_id' => SpotTypeEnum::class,
        'state' => SpotStateEnum::class,
        'building_type' => BuildingTypeEnum::class,
    ];
    
    /**
     * Get the human-readable spot type label
     */
    public function getSpotTypeLabel(): string
    {
        return $this->spot_type_id->label();
    }
    
    /**
     * Check if spot is published
     */
    public function isPublished(): bool
    {
        return $this->state === SpotStateEnum::PUBLIC;
    }
}
```

### Example 4: Using Enums in Seeders

```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Spot;
use spot2\SpotValidation\Enums\Spot\SpotTypeEnum;
use spot2\SpotValidation\Enums\Spot\SpotStateEnum;
use spot2\SpotValidation\Enums\Spot\BuildingTypeEnum;

class SpotSeeder extends Seeder
{
    public function run(): void
    {
        Spot::create([
            'name' => 'Industrial Warehouse',
            'spot_type_id' => SpotTypeEnum::INDUSTRIAL->value,
            'state' => SpotStateEnum::PUBLIC->value,
            'building_type' => BuildingTypeEnum::STEEL_AND_CONCRETE->value,
            'square_space' => 1000.00,
            // ... other fields
        ]);
    }
}
```

### Example 5: Getting Catalog Data for Dropdowns

```php
namespace App\Http\Controllers;

use spot2\SpotValidation\Enums\Spot\BuildingTypeEnum;
use spot2\SpotValidation\Enums\Spot\SpotStateEnum;
use spot2\SpotValidation\Enums\Spot\Price\PriceTypeEnum;

class CatalogController extends Controller
{
    /**
     * Get all catalogs for form dropdowns
     */
    public function index()
    {
        return response()->json([
            'building_types' => $this->getEnumOptions(BuildingTypeEnum::class),
            'spot_states' => $this->getEnumOptions(SpotStateEnum::class),
            'price_types' => $this->getEnumOptions(PriceTypeEnum::class),
        ]);
    }
    
    /**
     * Convert enum to dropdown options
     */
    private function getEnumOptions(string $enumClass): array
    {
        $options = [];
        
        foreach ($enumClass::cases() as $case) {
            $options[] = [
                'id' => $case->value,
                'label' => $case->label(),
                'name' => $case->name,
            ];
        }
        
        return $options;
    }
}
```

---

## Custom Form Requests

### Example 6: Creating a Custom Request for Specific Spot Type

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use spot2\SpotValidation\Http\Requests\Rules\SharedRules;
use spot2\SpotValidation\Http\Requests\Rules\IndustrialRules;
use Illuminate\Validation\Rule;

class IndustrialSpotRequest extends FormRequest
{
    use SharedRules;
    use IndustrialRules;
    
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return array_merge(
            $this->sharedRules(),
            $this->priceRules(),
            $this->industrialRules(),
            [
                // Add your custom rules here
                'custom_field' => 'required|string|max:255',
            ]
        );
    }
    
    public function messages(): array
    {
        return [
            'custom_field.required' => 'El campo personalizado es requerido.',
        ];
    }
}
```

### Example 7: Conditional Validation Based on Spot Type

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use spot2\SpotValidation\Enums\Spot\SpotTypeEnum;
use spot2\SpotValidation\Http\Requests\Rules\SharedRules;
use spot2\SpotValidation\Http\Requests\Rules\IndustrialRules;
use spot2\SpotValidation\Http\Requests\Rules\OfficeRules;

class DynamicSpotRequest extends FormRequest
{
    use SharedRules, IndustrialRules, OfficeRules;
    
    public function rules(): array
    {
        $rules = array_merge(
            $this->sharedRules(),
            $this->priceRules()
        );
        
        // Add spot-type specific rules
        $spotType = $this->input('spot_type_id');
        
        if ($spotType === SpotTypeEnum::INDUSTRIAL->value) {
            $rules = array_merge($rules, $this->industrialRules());
        } elseif ($spotType === SpotTypeEnum::OFFICE->value) {
            $rules = array_merge($rules, $this->officeRules());
        }
        
        return $rules;
    }
}
```

---

## API Responses with Enums

### Example 8: Formatting Enum Data for API Responses

```php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpotResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            
            // Include both ID and label for frontend
            'spot_type' => [
                'id' => $this->spot_type_id->value,
                'label' => $this->spot_type_id->label(),
                'name' => $this->spot_type_id->name,
            ],
            
            // Or use the helper method
            'state' => $this->state->getApiResponseObject(),
            
            'building_type' => $this->building_type?->getApiResponseObject(),
            
            'square_space' => $this->square_space,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
```

### Example 9: Creating Select Options Endpoint

```php
namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use spot2\SpotValidation\Enums\Spot\BuildingTypeEnum;
use spot2\SpotValidation\Enums\Spot\BuildingStatusEnum;
use spot2\SpotValidation\Enums\Spot\BuildingClassEnum;

class SelectOptionsController extends Controller
{
    /**
     * Get all select options for spot forms
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'buildingTypes' => array_map(
                fn($case) => $case->getApiResponseObject(),
                BuildingTypeEnum::cases()
            ),
            'buildingStatuses' => array_map(
                fn($case) => $case->getApiResponseObject(),
                BuildingStatusEnum::cases()
            ),
            'buildingClasses' => array_map(
                fn($case) => $case->getApiResponseObject(),
                BuildingClassEnum::cases()
            ),
        ]);
    }
}
```

---

## Validation in Controllers

### Example 10: Manual Validation with Enums

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use spot2\SpotValidation\Enums\Spot\BuildingTypeEnum;
use spot2\SpotValidation\Enums\Spot\SpotStateEnum;

class QuickSpotController extends Controller
{
    public function quickUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'building_type' => [
                'sometimes',
                'integer',
                Rule::in(BuildingTypeEnum::getValues())
            ],
            'state' => [
                'sometimes',
                'integer',
                Rule::in(SpotStateEnum::getValues())
            ],
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Process validated data
        $spot = Spot::findOrFail($id);
        $spot->update($validator->validated());
        
        return response()->json($spot);
    }
}
```

### Example 11: Bulk Operations with Enum Validation

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use spot2\SpotValidation\Enums\Spot\SpotStateEnum;
use App\Models\Spot;

class BulkSpotController extends Controller
{
    /**
     * Bulk update spot states
     */
    public function bulkUpdateState(Request $request)
    {
        $validated = $request->validate([
            'spot_ids' => 'required|array',
            'spot_ids.*' => 'required|integer|exists:spots,id',
            'state' => [
                'required',
                'integer',
                Rule::in(SpotStateEnum::getValues())
            ],
        ]);
        
        $stateEnum = SpotStateEnum::from($validated['state']);
        
        Spot::whereIn('id', $validated['spot_ids'])
            ->update(['state' => $stateEnum->value]);
        
        return response()->json([
            'message' => "Spots updated to state: {$stateEnum->label()}",
            'count' => count($validated['spot_ids'])
        ]);
    }
}
```

### Example 12: Filtering with Enums

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use spot2\SpotValidation\Enums\Spot\SpotTypeEnum;
use spot2\SpotValidation\Enums\Spot\SpotStateEnum;

class SpotSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = Spot::query();
        
        // Filter by spot type
        if ($request->has('spot_type')) {
            $spotType = SpotTypeEnum::tryFrom($request->spot_type);
            if ($spotType) {
                $query->where('spot_type_id', $spotType->value);
            }
        }
        
        // Filter by state
        if ($request->has('state')) {
            $state = SpotStateEnum::tryFrom($request->state);
            if ($state) {
                $query->where('state', $state->value);
            }
        }
        
        $spots = $query->paginate(15);
        
        return response()->json($spots);
    }
}
```

---

## Testing with Enums

### Example 13: Feature Tests

```php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Spot;
use spot2\SpotValidation\Enums\Spot\SpotTypeEnum;
use spot2\SpotValidation\Enums\Spot\SpotStateEnum;

class SpotControllerTest extends TestCase
{
    public function test_can_create_spot_with_valid_data()
    {
        $response = $this->postJson('/api/spots', [
            'name' => 'Test Spot',
            'spot_type_id' => SpotTypeEnum::INDUSTRIAL->value,
            'state' => SpotStateEnum::DRAFT->value,
            'square_space' => 1000,
            // ... other required fields
        ]);
        
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => ['id', 'name', 'spot_type_id']
        ]);
    }
    
    public function test_cannot_create_spot_with_invalid_spot_type()
    {
        $response = $this->postJson('/api/spots', [
            'name' => 'Test Spot',
            'spot_type_id' => 999, // Invalid spot type
            'square_space' => 1000,
        ]);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['spot_type_id']);
    }
}
```

---

## Configuration Examples

### Example 14: Using Package Configuration

```php
namespace App\Services;

use spot2\SpotValidation\Enums\Spot\Price\PriceCurrencyTypeEnum;

class PriceCalculationService
{
    public function convertPrice(float $amount, int $fromCurrency, int $toCurrency): float
    {
        // Use package config for exchange rate
        $exchangeRate = config('spot-validation.pricing.default_exchange_rate', 20.00);
        
        $from = PriceCurrencyTypeEnum::from($fromCurrency);
        $to = PriceCurrencyTypeEnum::from($toCurrency);
        
        if ($from === PriceCurrencyTypeEnum::USD && $to === PriceCurrencyTypeEnum::MXN) {
            return $amount * $exchangeRate;
        }
        
        if ($from === PriceCurrencyTypeEnum::MXN && $to === PriceCurrencyTypeEnum::USD) {
            return $amount / $exchangeRate;
        }
        
        return $amount;
    }
}
```

---

These examples demonstrate the most common use cases for the Spot Validation package. For more detailed information, refer to the main README.md file.


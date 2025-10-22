<?php

namespace Spot2HQ\SpotValidation\Tests\Unit\Enums;

use Spot2HQ\SpotValidation\Tests\TestCase;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceCurrencyTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceAreaTypeEnum;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceModalityEnum;
use Spot2HQ\SpotValidation\Enums\Spot\Price\PriceMaintenanceTypeEnum;

/**
 * Test Price-related Enums
 * 
 * @group unit
 * @group enums
 * @group price
 */
class PriceEnumsTest extends TestCase
{
    public function test_price_type_enum_has_correct_values(): void
    {
        $this->assertSame(1, PriceTypeEnum::RENT->value);
        $this->assertSame(2, PriceTypeEnum::SALE->value);
        $this->assertSame(3, PriceTypeEnum::RENT_AND_SALE->value);
    }

    public function test_price_type_enum_returns_correct_labels(): void
    {
        $this->assertSame('Renta', PriceTypeEnum::RENT->label());
        $this->assertSame('Venta', PriceTypeEnum::SALE->label());
        $this->assertSame('Renta y Venta', PriceTypeEnum::RENT_AND_SALE->label());
    }

    public function test_currency_type_enum_has_correct_values(): void
    {
        $this->assertSame(1, PriceCurrencyTypeEnum::MXN->value);
        $this->assertSame(2, PriceCurrencyTypeEnum::USD->value);
    }

    public function test_currency_type_enum_returns_correct_labels(): void
    {
        $this->assertSame('MXN', PriceCurrencyTypeEnum::MXN->label());
        $this->assertSame('USD', PriceCurrencyTypeEnum::USD->label());
    }

    public function test_currency_type_enum_returns_correct_symbols(): void
    {
        $this->assertSame('$', PriceCurrencyTypeEnum::MXN->symbol());
        $this->assertSame('USD $', PriceCurrencyTypeEnum::USD->symbol());
    }

    public function test_area_type_enum_has_correct_values(): void
    {
        $this->assertSame(1, PriceAreaTypeEnum::TOTAL->value);
        $this->assertSame(2, PriceAreaTypeEnum::PER_SQUARE_METER->value);
        $this->assertSame(3, PriceAreaTypeEnum::PERCENTAGE->value);
    }

    public function test_area_type_enum_returns_correct_labels(): void
    {
        $this->assertSame('total', PriceAreaTypeEnum::TOTAL->label());
        $this->assertSame('por metro cuadrado', PriceAreaTypeEnum::PER_SQUARE_METER->label());
        $this->assertSame('por porcentaje', PriceAreaTypeEnum::PERCENTAGE->label());
    }

    public function test_modality_enum_has_correct_values(): void
    {
        $this->assertSame(1, PriceModalityEnum::DAILY_WEEKLY->value);
        $this->assertSame(2, PriceModalityEnum::MONTHLY->value);
        $this->assertSame(3, PriceModalityEnum::ANNUAL->value);
    }

    public function test_maintenance_type_enum_has_correct_values(): void
    {
        $this->assertSame(1, PriceMaintenanceTypeEnum::REAL_VALUE->value);
        $this->assertSame(2, PriceMaintenanceTypeEnum::PERCENTAGE_VALUE->value);
        $this->assertSame(3, PriceMaintenanceTypeEnum::SQUARE_METER_VALUE->value);
    }
}
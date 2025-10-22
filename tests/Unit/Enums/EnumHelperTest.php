<?php

namespace Spot2HQ\SpotValidation\Tests\Unit\Enums;

use Spot2HQ\SpotValidation\Tests\TestCase;
use Spot2HQ\SpotValidation\Enums\Spot\BuildingClassEnum;
use UnexpectedValueException;

/**
 * Test EnumHelper trait functionality
 * 
 * @group unit
 * @group enums
 * @group helper
 */
class EnumHelperTest extends TestCase
{
    public function test_it_returns_labels_array(): void
    {
        $labels = BuildingClassEnum::labels();
        
        $this->assertIsArray($labels);
        $this->assertArrayHasKey('A_PLUS', $labels);
        $this->assertArrayHasKey('A', $labels);
        $this->assertArrayHasKey('B', $labels);
        $this->assertArrayHasKey('C', $labels);
        
        $this->assertSame('A+', $labels['A_PLUS']);
        $this->assertSame('A', $labels['A']);
        $this->assertSame('B', $labels['B']);
        $this->assertSame('C', $labels['C']);
    }

    public function test_it_returns_keys_array(): void
    {
        $keys = BuildingClassEnum::getKeys();
        
        $this->assertIsArray($keys);
        $this->assertCount(4, $keys);
        $this->assertContains('A_PLUS', $keys);
        $this->assertContains('A', $keys);
        $this->assertContains('B', $keys);
        $this->assertContains('C', $keys);
    }

    public function test_it_returns_values_array(): void
    {
        $values = BuildingClassEnum::getValues();
        
        $this->assertIsArray($values);
        $this->assertCount(4, $values);
        $this->assertContains(1, $values);
        $this->assertContains(2, $values);
        $this->assertContains(3, $values);
        $this->assertContains(4, $values);
    }

    public function test_it_gets_value_by_key(): void
    {
        $value = BuildingClassEnum::getValue('A_PLUS');
        
        $this->assertSame(1, $value);
        
        $value = BuildingClassEnum::getValue('B');
        $this->assertSame(3, $value);
    }

    public function test_it_gets_key_by_value(): void
    {
        $key = BuildingClassEnum::getKey(1);
        
        $this->assertSame('A_PLUS', $key);
        
        $key = BuildingClassEnum::getKey(3);
        $this->assertSame('B', $key);
    }

    public function test_it_throws_exception_for_invalid_value_when_getting_key(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("Value '999' is not part of the enum");
        
        BuildingClassEnum::getKey(999);
    }

    public function test_it_returns_random_value(): void
    {
        $value = BuildingClassEnum::getRandomValue();
        
        $this->assertIsInt($value);
        $this->assertContains($value, [1, 2, 3, 4]);
    }

    public function test_it_returns_random_key(): void
    {
        $key = BuildingClassEnum::getRandomKey();
        
        $this->assertIsString($key);
        $this->assertContains($key, ['A_PLUS', 'A', 'B', 'C']);
    }

    public function test_it_converts_to_array(): void
    {
        $array = BuildingClassEnum::toArray();
        
        $this->assertIsArray($array);
        $this->assertCount(4, $array);
        $this->assertArrayHasKey('A_PLUS', $array);
        $this->assertArrayHasKey('A', $array);
        $this->assertSame(1, $array['A_PLUS']);
        $this->assertSame(2, $array['A']);
    }

    public function test_it_returns_api_response_object(): void
    {
        $response = BuildingClassEnum::A_PLUS->getApiResponseObject();
        
        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('label', $response);
        $this->assertSame(1, $response['id']);
        $this->assertSame('A+', $response['label']);
    }

    public function all_enum_helper_methods_are_static(): void
    {
        $reflection = new \ReflectionClass(BuildingClassEnum::class);
        
        $methods = ['labels', 'getKeys', 'getValues', 'getValue', 'getKey', 'getRandomValue', 'getRandomKey', 'toArray'];
        
        foreach ($methods as $methodName) {
            $method = $reflection->getMethod($methodName);
            $this->assertTrue($method->isStatic(), "Method {$methodName} should be static");
        }
    }
}


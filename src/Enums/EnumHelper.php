<?php

namespace Spot2HQ\SpotValidation\Enums;

use UnexpectedValueException;

/**
 * Provides helper functions for the enum classes
 * For now only to "translate" and to return the enum cases as array
 */
trait EnumHelper
{
    /**
     * This return the enum cases in a way is "translated"
     * with their respective label, it's important to properly
     * implements the LabelInterface
     *
     * @see LabelInterface
     */
    public static function labels(): array
    {
        $labels = [];
        foreach (self::cases() as $case) {
            $labels[$case->name] = $case->label();
        }

        return $labels;
    }

    /**
     * Legacy Helper Method
     * Our implementation of getting the keys of the enum cases
     */
    public static function getKeys(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Legacy Helper Method
     * Our implementation of getting the values of the enum cases
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Legacy Helper Method
     * Our implementation of getting the value of one case
     */
    public static function getValue(string $key): mixed
    {
        return self::toArray()[$key];
    }

    /**
     * Legacy Helper Method
     * Our implementation of getting the key of one case
     */
    public static function getKey(mixed $value): string
    {
        return array_search($value, self::toArray(), true)
            ?: throw new UnexpectedValueException("Value '$value' is not part of the enum ".static::class);
    }

    /**
     * Legacy Helper Method
     * Our implementation of getting a random value of the enum cases
     */
    public static function getRandomValue(): mixed
    {
        $values = self::getValues();

        return $values[array_rand($values)];
    }

    /**
     * Legacy Helper Method
     * Our implementation of getting a random key of the enum cases
     */
    public static function getRandomKey(): mixed
    {
        $keys = self::getKeys();

        return $keys[array_rand($keys)];
    }

    /**
     * In the legacy code this is similar to getConstants()
     * By default, the enum class returns with the Class types,
     * this helper function returns the enum cases key => value
     * but as array
     */
    public static function toArray(): array
    {
        $arr = [];
        foreach (self::cases() as $case) {
            $arr[$case->name] = $case->value;
        }

        return $arr;
    }

    /**
     * Get the API response object as an array.
     */
    public function getApiResponseObject(): array
    {
        return [
            'id' => $this->value,
            'label' => method_exists($this, 'label') ? $this->label() : null,  // @phpstan-ignore-line
        ];
    }
}

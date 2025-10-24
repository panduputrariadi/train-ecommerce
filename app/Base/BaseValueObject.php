<?php

namespace App\Base;

use Illuminate\Support\Str;
use JsonSerializable;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;

abstract class BaseValueObject implements JsonSerializable
{
    /**
     * Returns an array representation of the value object.
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    /**
     * Fill the value object with the given attributes.
     *
     * @param array $attributes The attributes to fill the value object with.
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Create a new instance of the class from the given array of data.
     *
     * @param array $attributes The data to use when creating the instance.
     * @return static A new instance of the class.
     */
    public static function fromArray(array $attributes): static
    {
        return new static($attributes);
    }

    /**
     * Fill the value object with the given attributes.
     *
     * This method iterates over the public properties of the value object
     * and attempts to fill them with the given attributes. The property
     * name is converted to snake_case, camelCase, and PascalCase
     * to allow for different naming conventions. The value is then cast
     * to the type of the property using the castValue method.
     *
     * @param array $attributes The attributes to fill the value object with.
     */
    protected function fill(array $attributes): void
    {
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propName = $property->getName();

            $snakeKey = Str::snake($propName);
            $camelKey = lcfirst(Str::studly($propName));
            $pascalKey = Str::studly($propName);

            $value = $attributes[$snakeKey]
                ?? $attributes[$camelKey]
                ?? $attributes[$pascalKey]
                ?? $attributes[$propName]
                ?? null;

            $type = $property->getType();

            if ($type instanceof ReflectionNamedType) {
                $typeName = $type->getName();

                if ($value === null && !$type->allowsNull()) {
                    continue;
                }

                $this->{$propName} = $this->castValue($typeName, $value);
            } else {
                $this->{$propName} = $value;
            }
        }
    }

    /**
     * Casts the given value to the given type.
     *
     * If the given type is a primitive type (int, float, bool, string, array),
     * the value is cast to that type. If the given type is an object type,
     * the value is passed to the constructor of that type. If the value is null
     * and the type does not allow null, the method will return null.
     *
     * @param string $typeName The type to cast the value to.
     * @param mixed $value The value to cast.
     * @return mixed The cast value.
     */
    protected function castValue(string $typeName, mixed $value): mixed
    {
        return match ($typeName) {
            'int' => $value !== null ? (int) $value : null,
            'float' => $value !== null ? (float) $value : null,
            'bool' => $value !== null
                ? filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                : null,
            'string' => $value !== null ? (string) $value : null,
            'array' => $value !== null ? (array) $value : null,
            default => (class_exists($typeName) && is_array($value))
                ? new $typeName($value)
                : $value,
        };
    }

    /**
     * Returns an array representation of the value object.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $snakeName = Str::snake($property->getName());
            $value = $this->{$property->getName()};
            $array[$snakeName] = $this->transformValue($value);
        }

        return $array;
    }

    /**
     * Recursively transforms the given value into an array.
     *
     * If the given value is an array, the method will iterate over the
     * array and transform each value. If the given value is an object
     * and that object has a toArray method, the method will call that
     * method and return the result. Otherwise, the method will return the
     * value unchanged.
     *
     * @param mixed $value The value to transform.
     * @return mixed The transformed value.
     */
    protected function transformValue(mixed $value): mixed
    {
        if (is_array($value)) {
            return collect($value)
                ->mapWithKeys(fn($v, $k) => [
                    is_string($k) ? Str::snake($k) : $k => $this->transformValue($v)
                ])
                ->toArray();
        }

        if (is_object($value) && method_exists($value, 'toArray')) {
            return $value->toArray();
        }

        return $value;
    }

    /**
     * Compares two value objects for equality.
     *
     * @param self $other The other value object to compare with.
     * @return bool True if the two value objects are equal, false otherwise.
     */
    public function equals(self $other): bool
    {
        return $this->toArray() === $other->toArray();
    }
}

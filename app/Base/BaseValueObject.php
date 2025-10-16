<?php

namespace App\Base;

use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;

abstract class BaseValueObject
{
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public static function fromArray(array $attributes): static
    {
        return new static($attributes);
    }

    protected function fill(array $attributes): void
    {
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propName = $property->getName();
            $snakeKey = Str::snake($propName);
            $camelKey = Str::camel($propName);

            $value = $attributes[$snakeKey]
                ?? $attributes[$camelKey]
                ?? $attributes[$propName]
                ?? null;

            $type = $property->getType();

            if ($type instanceof ReflectionNamedType) {
                $typeName = $type->getName();
                if ($value === null && !$type->allowsNull()) {
                    continue;
                }

                switch ($typeName) {
                    case 'int':
                        $this->{$propName} = $value !== null ? (int) $value : null;
                        break;

                    case 'float':
                        $this->{$propName} = $value !== null ? (float) $value : null;
                        break;

                    case 'bool':
                        $this->{$propName} = $value !== null
                            ? filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                            : null;
                        break;

                    case 'string':
                        $this->{$propName} = $value !== null ? (string) $value : null;
                        break;

                    case 'array':
                        $this->{$propName} = $value !== null ? (array) $value : null;
                        break;

                    default:
                        if (class_exists($typeName) && is_array($value)) {
                            $this->{$propName} = new $typeName($value);
                        } else {
                            $this->{$propName} = $value;
                        }
                        break;
                }
            } else {
                $this->{$propName} = $value;
            }
        }
    }

    public function toArray(): array
    {
        $array = [];
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();
            $snakeName = Str::snake($name);
            $value = $this->{$name};

            if (is_object($value) && method_exists($value, 'toArray')) {
                $array[$snakeName] = $value->toArray();
            } else {
                $array[$snakeName] = $value;
            }
        }

        return $array;
    }

    /**
     * Membandingkan dua ValueObject.
     */
    public function equals(self $other): bool
    {
        return $this->toArray() === $other->toArray();
    }
}

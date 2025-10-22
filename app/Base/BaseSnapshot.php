<?php

namespace App\Base;

use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;

abstract class BaseSnapshot implements JsonSerializable
{
    /**
     * @var string[]|null
     */
    protected ?array $fillable = null;


    /**
     * @param Model $model
     * @return static
     */
    public static function fromModel(Model $model): static
    {
        $instance = new static();
        $reflection = new ReflectionClass($instance);

        $properties = $instance->fillable ?? collect($reflection->getProperties(ReflectionProperty::IS_PUBLIC))
            ->map(fn ($p) => $p->getName())
            ->all();

        foreach ($properties as $property) {
            if ($model->offsetExists($property) || isset($model->{$property})) {
                $instance->{$property} = $model->{$property};
            } elseif (method_exists($model, $property)) {
                $instance->{$property} = $model->{$property};
            } else {
                $instance->{$property} = null;
            }
        }

        return $instance;
    }


    /**
     * Returns an array representation of the snapshot.
     *
     * @return array
     */
    public function toArray(): array
    {
        $reflection = new ReflectionClass($this);
        $properties = $this->fillable ?? collect($reflection->getProperties(ReflectionProperty::IS_PUBLIC))
            ->map(fn ($p) => $p->getName())
            ->all();

        $data = [];
        foreach ($properties as $property) {
            $data[$property] = $this->{$property} ?? null;
        }

        return $data;
    }


     /**
     * Returns a JSON string representation of the snapshot.
     *
     * @param int $options The JSON encoding options.
     *
     * @return string
     */
    public function toJson(int $options = JSON_PRETTY_PRINT): string
    {
        return json_encode($this->toArray(), $options);
    }


    /**
     * Convert the object to its JSON representation.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Create a new instance from an array of data.
     *
     * @param array $data The data to use when creating the instance.
     *
     * @return static A new instance of the class.
     */
    public static function fromArray(array $data): static
    {
        $instance = new static();
        foreach ($data as $key => $value) {
            if (property_exists($instance, $key)) {
                $instance->{$key} = $value;
            }
        }

        return $instance;
    }
}

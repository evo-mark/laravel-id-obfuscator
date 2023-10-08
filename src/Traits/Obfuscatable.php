<?php

namespace EvoMark\LaravelIdObfuscator\Traits;

use Illuminate\Database\Eloquent\Model;
use EvoMark\LaravelIdObfuscator\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use EvoMark\LaravelIdObfuscator\Facades\Obfuscate;

trait Obfuscatable
{
    public function obfuscatedId(): Attribute
    {
        return Attribute::make(
            get: fn () => Obfuscate::encode($this->{$this->getKeyName()})
        );
    }

    public function resolveRouteBinding($value, $field = null): Model
    {
        // If a field is specified, use the parent implementation
        if (!empty($field) || empty($value)) {
            return parent::resolveRouteBinding($value, $field);
        }

        $id = Obfuscate::decode($value);
        $class = get_class($this);
        if (empty($id)) {
            abort(404);
        }

        return $class::findOrFail($id);
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return EvoMark\LaravelIdObfuscator\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    public function toArray()
    {
        $data = parent::toArray();

        // Add or modify data as needed
        $data[$this->getKeyName()] = Obfuscate::encode($data[$this->getKeyName()]);

        return $data;
    }
}

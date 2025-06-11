<?php

namespace EvoMark\LaravelIdObfuscator\Traits;

use SplFileObject;
use ReflectionMethod;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use EvoMark\LaravelIdObfuscator\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use EvoMark\LaravelIdObfuscator\Facades\Obfuscate;
use Illuminate\Database\Eloquent\Relations\Relation;

trait Obfuscatable
{

    protected $relationMethodsWithForeignKeys = [
        'belongsTo',
        'morphTo',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
     */
    protected function obfuscatedId(): Attribute
    {
        return Attribute::make(
            get: fn() => Obfuscate::encode($this->{$this->getKeyName()})
        );
    }

    public function resolveRouteBinding($value, $field = null): Model|null
    {
        // If a field is specified, use the parent implementation
        if (!empty($field) || empty($value)) {
            return parent::resolveRouteBinding($value, $field);
        }

        if (gettype($value) === 'string') {
            $value = Obfuscate::decode($value);
        }

        return $this->resolveRouteBindingQuery($this, $value, $field)->first();
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \EvoMark\LaravelIdObfuscator\Eloquent\Builder
     */
    public function newEloquentBuilder($query): Builder
    {
        return new Builder($query);
    }

    public function toArray()
    {
        $data = parent::toArray();

        // Add or modify data as needed
        $keyName = $this->getKeyName();
        if (isset($data[$keyName])) {
            $data[$keyName] = Obfuscate::encode($data[$keyName]);
        }

        if (config('obfuscate.encodeForeign') === true) {
            $foreignKeys = $this->getModelRelationsForeignKeys($this);
            foreach ($foreignKeys as $foreignKey) {
                if (!isset($data[$foreignKey])) continue;
                $data[$foreignKey] = Obfuscate::encode($data[$foreignKey]);
            }
        }

        return $data;
    }

     protected function getModelRelationsForeignKeys($model)
    {
        $cacheKey = get_class($model);

        return Cache::remember($cacheKey, CarbonInterval::months(3), function () use ($model) {
            return collect(get_class_methods($model))
                ->map(fn ($method) => new ReflectionMethod($model, $method))
                ->reject(
                    fn (ReflectionMethod $method) => $method->isStatic()
                        || $method->isAbstract()
                        || $method->getDeclaringClass()->getName() === Model::class
                )
                ->filter(function (ReflectionMethod $method) {
                    // This step filters out relationships that don't fit our allowed methods ($this->relationMethodsWithForeignKeys)
                    $file = new SplFileObject($method->getFileName());
                    $file->seek($method->getStartLine() - 1);
                    $code = '';
                    while ($file->key() < $method->getEndLine()) {
                        $code .= trim($file->current());
                        $file->next();
                    }

                    return collect($this->relationMethodsWithForeignKeys)
                        ->contains(fn ($relationMethod) => str_contains($code, '$this->'.$relationMethod.'('));
                })
                ->map(function (ReflectionMethod $method) use ($model) {
                    $relation = $method->invoke($model);

                    if (! $relation instanceof Relation) {
                        return null;
                    } elseif (in_array(Obfuscatable::class, class_uses_recursive(get_class($relation->getRelated())))) {
                        return null;
                    }

                    return $relation->getForeignKeyName();
                })
                ->filter()
                ->values();
        });

    }
}

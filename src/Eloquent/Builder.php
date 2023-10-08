<?php

namespace EvoMark\LaravelIdObfuscator\Eloquent;

use EvoMark\LaravelIdObfuscator\Facades\Obfuscate;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder as FoundationBuilder;

class Builder extends FoundationBuilder
{
    /**
     * Add a where clause on the primary key to the query.
     *
     * @param  mixed  $id
     * @return $this
     */
    public function whereKey($id)
    {
        if (is_array($id) || $id instanceof Arrayable) {
            $id = Arr::map($id, function ($item) {
                return gettype($item) === 'string' ? Obfuscate::decode($item) : $item;
            });
        } else if ($id !== null && gettype($id) === "string") {
            $id = Obfuscate::decode($id);
        }

        return parent::whereKey($id);
    }

    /**
     * Add a where clause on the primary key to the query.
     *
     * @param  mixed  $id
     * @return $this
     */
    public function whereKeyNot($id)
    {
        if (is_array($id) || $id instanceof Arrayable) {
            $id = Arr::map($id, function ($item) {
                return gettype($item) === 'string' ? Obfuscate::decode($item) : $item;
            });
        } else if ($id !== null && gettype($id) === "string") {
            $id = Obfuscate::decode($id);
        }

        return parent::whereKeyNot($id);
    }
}

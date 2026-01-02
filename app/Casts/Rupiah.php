<?php

namespace App\Casts;

use App\ValueObjects\FormatRupiah;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class Rupiah implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return '-';
        }

        return FormatRupiah::fromInt((int) $value)->format();
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null || $value === '' || $value === '-') {
            return null;
        }

        return FormatRupiah::fromString($value)->toInt();
    }
}

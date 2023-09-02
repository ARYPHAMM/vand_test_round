<?php

namespace App\Infrastructure\Traits;

use Illuminate\Support\Str;

trait UuidTrait
{
    protected static function bootUuidTrait()
    {
        static::creating(function ($model) {
            $model->keyType = 'string';
            $model->incrementing = false;

            $model->{$model->getKeyName()} = $model->{$model->getKeyName()} ?: (string) Str::uuid();
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function getKeyName()
    {
        return 'id';
    }
}

<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Uuidable {
    protected static function boot() {
        parent::boot();

        static::creating(function (Model $model) {
            $model->keyType = 'string';
            $model->primaryKey = 'id';
            $model->incrementing = false;

            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid4()->toString();
            }
        });
    }
}

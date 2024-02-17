<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

trait Uuidable {
    protected static function boot() {
        parent::boot();

        static::creating(function (Model $model) {
            $model->{$model->getKeyName()} = Uuid::uuid4()->toString();
        });
    }
}

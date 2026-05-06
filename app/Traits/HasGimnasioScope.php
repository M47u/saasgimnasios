<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasGimnasioScope
{
    protected static function bootHasGimnasioScope(): void
    {
        static::addGlobalScope('gimnasio', function (Builder $query) {
            if (auth('gym')->check()) {
                $query->where(
                    (new static)->getTable() . '.gimnasio_id',
                    auth('gym')->user()->gimnasio_id
                );
            }

            if (auth('member')->check()) {
                $query->where(
                    (new static)->getTable() . '.gimnasio_id',
                    auth('member')->user()->gimnasio_id
                );
            }
        });
    }

    public function scopeDelGimnasio(Builder $query, int $gimnasioId): Builder
    {
        return $query->where($this->getTable() . '.gimnasio_id', $gimnasioId);
    }
}

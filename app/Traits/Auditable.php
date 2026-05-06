<?php

namespace App\Traits;

use App\Models\LogAuditoria;

trait Auditable
{
    protected static function bootAuditable(): void
    {
        static::created(function ($model) {
            static::registrarLog('crear', $model, null, $model->toArray());
        });

        static::updated(function ($model) {
            static::registrarLog('editar', $model, $model->getOriginal(), $model->getDirty());
        });

        static::deleted(function ($model) {
            static::registrarLog('eliminar', $model, $model->toArray(), null);
        });
    }

    protected static function registrarLog(string $accion, $model, ?array $anterior, ?array $nuevo): void
    {
        $user     = null;
        $userType = null;

        foreach (['saas', 'gym', 'member'] as $guard) {
            if (auth($guard)->check()) {
                $user     = auth($guard)->user();
                $userType = match ($guard) {
                    'saas'   => 'saas_users',
                    'gym'    => 'gym_users',
                    'member' => 'member_users',
                };
                break;
            }
        }

        if (! $user) return;

        LogAuditoria::create([
            'gimnasio_id'    => $model->gimnasio_id ?? null,
            'user_id'        => $user->id,
            'user_type'      => $userType,
            'accion'         => $accion,
            'modelo'         => class_basename($model),
            'modelo_id'      => $model->id,
            'valor_anterior' => $anterior,
            'valor_nuevo'    => $nuevo,
            'ip'             => request()->ip(),
            'user_agent'     => request()->userAgent(),
            'created_at'     => now(),
        ]);
    }
}

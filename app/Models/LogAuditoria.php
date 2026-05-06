<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class LogAuditoria extends Model
{
    protected $table = 'logs_auditoria';

    public $timestamps = false;

    protected $fillable = [
        'gimnasio_id', 'user_id', 'user_type',
        'accion', 'modelo', 'modelo_id',
        'valor_anterior', 'valor_nuevo',
        'ip', 'user_agent', 'created_at',
    ];

    protected $casts = [
        'valor_anterior' => 'array',
        'valor_nuevo'    => 'array',
        'created_at'     => 'datetime',
    ];

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function usuario(): Model|null
    {
        return match ($this->user_type) {
            'saas_users'   => SaasUser::find($this->user_id),
            'gym_users'    => GymUser::find($this->user_id),
            'member_users' => MemberUser::find($this->user_id),
            default        => null,
        };
    }

    public function scopeDelModelo(Builder $query, string $modelo): Builder
    {
        return $query->where('modelo', $modelo);
    }

    public function scopeDelGimnasio(Builder $query, int $id): Builder
    {
        return $query->where('gimnasio_id', $id);
    }

    public function scopeDeHoy(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }
}

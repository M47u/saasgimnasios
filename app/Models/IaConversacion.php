<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasGimnasioScope;

class IaConversacion extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'gimnasio_id', 'socio_id', 'gym_user_id',
        'contexto', 'mensajes', 'tokens_usados',
    ];

    protected $casts = [
        'mensajes' => 'array',
    ];

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function gymUser(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'gym_user_id');
    }

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function agregarMensaje(string $role, string $content, int $tokens = 0): void
    {
        $mensajes   = $this->mensajes ?? [];
        $mensajes[] = ['role' => $role, 'content' => $content];

        $this->update([
            'mensajes'      => $mensajes,
            'tokens_usados' => $this->tokens_usados + $tokens,
        ]);
    }

    public function scopeDelMes($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }
}

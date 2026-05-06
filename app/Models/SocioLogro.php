<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasGimnasioScope;

class SocioLogro extends Model
{
    use HasGimnasioScope;

    public $timestamps = false;

    protected $fillable = [
        'socio_id', 'logro_id', 'gimnasio_id', 'desbloqueado_at',
    ];

    protected $casts = [
        'desbloqueado_at' => 'datetime',
    ];

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function logro(): BelongsTo
    {
        return $this->belongsTo(Logro::class, 'logro_id');
    }
}

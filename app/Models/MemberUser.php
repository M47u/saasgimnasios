<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class MemberUser extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'socio_id', 'gimnasio_id', 'email', 'password',
        'qr_token', 'onboarding_completo',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'onboarding_completo' => 'boolean',
        'email_verified_at'   => 'datetime',
        'last_login'          => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->qr_token = $model->qr_token ?? Str::random(64);
        });
    }

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function regenerarQr(): void
    {
        $this->update(['qr_token' => Str::random(64)]);
    }
}

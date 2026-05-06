<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketRespuesta extends Model
{
    protected $fillable = ['ticket_id', 'saas_user_id', 'gym_user_id', 'mensaje'];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(TicketSoporte::class, 'ticket_id');
    }

    public function saasUser(): BelongsTo
    {
        return $this->belongsTo(SaasUser::class, 'saas_user_id');
    }

    public function gymUser(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'gym_user_id');
    }

    public function autor(): BelongsTo
    {
        return $this->saas_user_id
            ? $this->saasUser()
            : $this->gymUser();
    }
}

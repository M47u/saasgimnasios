<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('saas_plans')->restrictOnDelete();
            $table->enum('ciclo', ['mensual', 'anual'])->default('mensual');
            $table->date('inicio');
            $table->date('fin');
            $table->date('trial_ends_at')->nullable()->comment('Fin del período de prueba');
            $table->enum('estado', ['trial', 'activa', 'vencida', 'suspendida', 'cancelada'])->default('trial');
            $table->decimal('monto_pagado', 8, 2)->nullable();
            $table->string('comprobante')->nullable()->comment('Número o path del comprobante de transferencia');
            $table->foreignId('registrado_por')->nullable()->constrained('saas_users')->nullOnDelete();
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index(['empresa_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suscripciones');
    }
};

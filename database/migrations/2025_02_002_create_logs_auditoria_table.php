<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs_auditoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->nullable()->constrained('gimnasios')->nullOnDelete()
                  ->comment('Null para acciones del Admin SaaS global');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type')->comment('saas_users | gym_users | member_users');
            $table->string('accion')->comment('Ej: crear, editar, eliminar, suspender, activar');
            $table->string('modelo')->comment('Ej: Socio, Membresia, Pago');
            $table->unsignedBigInteger('modelo_id')->nullable();
            $table->json('valor_anterior')->nullable();
            $table->json('valor_nuevo')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at');

            $table->index(['user_id', 'user_type']);
            $table->index(['modelo', 'modelo_id']);
            $table->index('gimnasio_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs_auditoria');
    }
};

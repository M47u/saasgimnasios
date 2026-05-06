<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets_soporte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->foreignId('gym_user_id')->constrained('gym_users')->cascadeOnDelete();
            $table->string('asunto');
            $table->text('descripcion');
            $table->enum('estado', ['abierto', 'en_proceso', 'resuelto', 'cerrado'])->default('abierto');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente'])->default('media');
            $table->foreignId('asignado_a')->nullable()->constrained('saas_users')->nullOnDelete();
            $table->timestamps();

            $table->index(['estado', 'prioridad']);
            $table->index('gimnasio_id');
        });

        Schema::create('ticket_respuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets_soporte')->cascadeOnDelete();
            $table->foreignId('saas_user_id')->nullable()->constrained('saas_users')->nullOnDelete();
            $table->foreignId('gym_user_id')->nullable()->constrained('gym_users')->nullOnDelete();
            $table->text('mensaje');
            $table->timestamps();

            $table->index('ticket_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_respuestas');
        Schema::dropIfExists('tickets_soporte');
    }
};

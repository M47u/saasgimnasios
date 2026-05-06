<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gimnasios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->nullOnDelete()
                  ->comment('Null si es gimnasio independiente');
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('provincia')->nullable();
            $table->string('pais')->default('AR');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('logo_path')->nullable();
            $table->json('horarios')->nullable()->comment('Ej: {"lunes": "08:00-22:00", ...}');
            $table->json('config_notificaciones')->nullable()->comment('Días de aviso antes del vencimiento');
            $table->text('mp_access_token')->nullable()->comment('Encriptado — ver cast en modelo');
            $table->text('politicas')->nullable();
            $table->enum('estado', ['activo', 'suspendido', 'trial', 'cancelado'])->default('trial');
            $table->timestamps();

            $table->index('empresa_id');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gimnasios');
    }
};

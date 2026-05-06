<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gym_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('apellido')->nullable();
            $table->string('email');
            $table->string('password');
            $table->enum('rol', ['admin', 'recepcionista', 'entrenador'])->default('recepcionista');
            $table->boolean('activo')->default(true);
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->unique(['gimnasio_id', 'email']);
            $table->index(['gimnasio_id', 'rol']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_users');
    }
};

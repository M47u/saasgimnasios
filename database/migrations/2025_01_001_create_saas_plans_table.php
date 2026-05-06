<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saas_plans', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->decimal('precio_mensual', 8, 2);
            $table->decimal('precio_anual', 8, 2);
            $table->unsignedInteger('max_socios')->comment('0 = ilimitado');
            $table->unsignedInteger('max_usuarios')->comment('0 = ilimitado');
            $table->unsignedInteger('max_sucursales')->comment('0 = ilimitado');
            $table->unsignedInteger('limite_ia_mensual')->default(0)->comment('0 = sin IA');
            $table->json('modulos_habilitados')->nullable()->comment('Array de slugs de módulos');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saas_plans');
    }
};

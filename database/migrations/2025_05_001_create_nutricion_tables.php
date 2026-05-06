<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planes_nutricionales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
            $table->foreignId('generado_por')->nullable()->constrained('gym_users')->nullOnDelete()
                  ->comment('Entrenador que disparó la generación');
            $table->foreignId('editado_por')->nullable()->constrained('gym_users')->nullOnDelete()
                  ->comment('Último en editar el plan');
            $table->string('titulo');
            $table->text('observaciones')->nullable();
            $table->unsignedSmallInteger('calorias_totales')->nullable();
            $table->unsignedSmallInteger('proteinas_g')->nullable();
            $table->unsignedSmallInteger('carbohidratos_g')->nullable();
            $table->unsignedSmallInteger('grasas_g')->nullable();
            $table->unsignedSmallInteger('agua_diaria_ml')->nullable()->comment('Ingesta de agua recomendada en ml');
            $table->json('prompt_contexto')->nullable()->comment('Snapshot del perfil usado para generar con IA');
            $table->enum('estado', ['borrador', 'activo', 'archivado'])->default('borrador');
            $table->json('snapshot')->nullable()->comment('Copia JSON del plan al momento de activarse');
            $table->timestamp('generado_at')->nullable();
            $table->timestamp('activado_at')->nullable();
            $table->timestamps();

            $table->index(['socio_id', 'estado']);
            $table->index('gimnasio_id');
        });

        Schema::create('comidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('planes_nutricionales')->cascadeOnDelete();
            $table->enum('tipo', ['desayuno', 'almuerzo', 'merienda', 'cena', 'colacion_am', 'colacion_pm'])->default('desayuno');
            $table->unsignedTinyInteger('orden')->default(1);
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->unsignedSmallInteger('calorias')->nullable();
            $table->unsignedSmallInteger('proteinas_g')->nullable();
            $table->unsignedSmallInteger('carbohidratos_g')->nullable();
            $table->unsignedSmallInteger('grasas_g')->nullable();
            $table->timestamps();

            $table->index('plan_id');
        });

        Schema::create('alimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comida_id')->constrained('comidas')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('cantidad')->comment('Ej: 200, 1/2, 3');
            $table->string('unidad')->comment('Ej: g, ml, unidad, taza');
            $table->unsignedSmallInteger('calorias')->nullable();
            $table->timestamps();

            $table->index('comida_id');
        });

        Schema::create('plan_nutricional_comentarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('planes_nutricionales')->cascadeOnDelete();
            $table->foreignId('comida_id')->nullable()->constrained('comidas')->cascadeOnDelete()
                  ->comment('Null si el comentario es sobre el plan completo');
            $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->text('texto');
            $table->boolean('revisado')->default(false);
            $table->foreignId('revisado_por')->nullable()->constrained('gym_users')->nullOnDelete();
            $table->timestamp('revisado_at')->nullable();
            $table->timestamps();

            $table->index(['gimnasio_id', 'revisado']);
            $table->index('plan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_nutricional_comentarios');
        Schema::dropIfExists('alimentos');
        Schema::dropIfExists('comidas');
        Schema::dropIfExists('planes_nutricionales');
    }
};

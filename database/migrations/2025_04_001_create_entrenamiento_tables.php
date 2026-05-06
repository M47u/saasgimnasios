<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ejercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('grupo_muscular', [
                'pecho', 'espalda', 'hombros', 'biceps', 'triceps',
                'piernas', 'gluteos', 'abdomen', 'cardio', 'fullbody', 'otro'
            ])->default('otro');
            $table->string('video_url')->nullable()->comment('YouTube embed o video propio');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['gimnasio_id', 'grupo_muscular']);
        });

        Schema::create('rutinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->foreignId('entrenador_id')->nullable()->constrained('gym_users')->nullOnDelete();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('objetivo', ['fuerza', 'hipertrofia', 'resistencia', 'perdida_peso', 'rehabilitacion', 'general'])->default('general');
            $table->enum('nivel', ['principiante', 'intermedio', 'avanzado'])->default('principiante');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('gimnasio_id');
        });

        Schema::create('rutina_ejercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rutina_id')->constrained('rutinas')->cascadeOnDelete();
            $table->foreignId('ejercicio_id')->constrained('ejercicios')->restrictOnDelete();
            $table->unsignedTinyInteger('orden')->default(1);
            $table->unsignedTinyInteger('series')->default(3);
            $table->string('repeticiones')->comment('Ej: 10, 10-12, 30seg');
            $table->unsignedSmallInteger('descanso_seg')->default(60);
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('rutina_id');
        });

        Schema::create('socio_rutinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
            $table->foreignId('rutina_id')->constrained('rutinas')->restrictOnDelete();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->foreignId('asignado_por')->nullable()->constrained('gym_users')->nullOnDelete();
            $table->date('asignada_el');
            $table->date('fin')->nullable();
            $table->enum('estado', ['activa', 'completada', 'cancelada'])->default('activa');
            $table->timestamps();

            $table->index(['socio_id', 'estado']);
            $table->index('gimnasio_id');
        });

        Schema::create('ejercicio_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
            $table->foreignId('rutina_ejercicio_id')->constrained('rutina_ejercicios')->cascadeOnDelete();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->enum('tipo', ['dolor', 'dificultad', 'completado', 'consulta'])->default('completado');
            $table->text('nota')->nullable();
            $table->boolean('revisado')->default(false);
            $table->foreignId('revisado_por')->nullable()->constrained('gym_users')->nullOnDelete();
            $table->timestamp('revisado_at')->nullable();
            $table->timestamps();

            $table->index(['gimnasio_id', 'revisado']);
            $table->index('socio_id');
        });

        Schema::create('medidas_corporales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->decimal('peso_kg', 5, 2)->nullable();
            $table->decimal('altura_cm', 5, 2)->nullable();
            $table->decimal('cintura_cm', 5, 2)->nullable();
            $table->decimal('cadera_cm', 5, 2)->nullable();
            $table->decimal('pecho_cm', 5, 2)->nullable();
            $table->decimal('brazo_cm', 5, 2)->nullable();
            $table->decimal('muslo_cm', 5, 2)->nullable();
            $table->string('foto_path')->nullable()->comment('Foto de progreso — privada');
            $table->enum('fuente', ['gym', 'socio'])->default('gym')->comment('Quién cargó la medida');
            $table->boolean('validado_por_gym')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['socio_id', 'created_at']);
            $table->index('gimnasio_id');
        });

        Schema::create('logros', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique()->comment('Ej: racha_10_dias, tres_meses_activo');
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('icono')->nullable()->comment('Nombre del icono o path del SVG');
            $table->enum('criterio', ['asistencias_consecutivas', 'meses_activo', 'clases_asistidas', 'objetivo_alcanzado'])->default('asistencias_consecutivas');
            $table->unsignedInteger('valor_objetivo')->comment('Ej: 10 (días), 3 (meses)');
            $table->timestamps();
        });

        Schema::create('socio_logros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
            $table->foreignId('logro_id')->constrained('logros')->cascadeOnDelete();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->timestamp('desbloqueado_at');

            $table->unique(['socio_id', 'logro_id']);
            $table->index('gimnasio_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('socio_logros');
        Schema::dropIfExists('logros');
        Schema::dropIfExists('medidas_corporales');
        Schema::dropIfExists('ejercicio_feedback');
        Schema::dropIfExists('socio_rutinas');
        Schema::dropIfExists('rutina_ejercicios');
        Schema::dropIfExists('rutinas');
        Schema::dropIfExists('ejercicios');
    }
};

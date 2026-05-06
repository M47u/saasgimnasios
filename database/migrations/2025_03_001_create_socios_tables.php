<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('socios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('dni')->nullable();
            $table->string('foto_path')->nullable();
            $table->enum('objetivo', ['bajar_peso', 'ganar_masa', 'mantenerse', 'mejorar_resistencia', 'rehabilitacion'])->nullable();
            $table->enum('nivel', ['principiante', 'intermedio', 'avanzado'])->nullable();
            $table->unsignedTinyInteger('frecuencia_semanal')->nullable()->comment('Días por semana que entrena');
            $table->text('restricciones_alimentarias')->nullable()->comment('Alergias, preferencias, intolerancias');
            $table->unsignedSmallInteger('racha_actual')->default(0)->comment('Días de asistencia consecutivos');
            $table->date('racha_ultima_asistencia')->nullable();
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['activo', 'inactivo', 'baja'])->default('activo');
            $table->timestamps();

            $table->index(['gimnasio_id', 'estado']);
            $table->index('gimnasio_id');
        });

        Schema::create('member_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('qr_token', 64)->unique()->comment('Token único para QR de asistencia');
            $table->boolean('onboarding_completo')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('gimnasio_id');
        });

        Schema::create('planes_membresia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->string('nombre');
            $table->decimal('precio', 10, 2);
            $table->unsignedSmallInteger('duracion_dias')->comment('Ej: 30, 60, 90, 365');
            $table->boolean('incluye_clases')->default(false);
            $table->unsignedTinyInteger('dias_acceso_semana')->default(7)->comment('Días permitidos por semana');
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['gimnasio_id', 'activo']);
        });

        Schema::create('membresias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('planes_membresia')->restrictOnDelete();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->date('inicio');
            $table->date('fin');
            $table->unsignedSmallInteger('dias_congelados')->default(0);
            $table->date('congelada_desde')->nullable();
            $table->enum('estado', ['activa', 'vencida', 'congelada', 'cancelada'])->default('activa');
            $table->foreignId('registrado_por')->nullable()->constrained('gym_users')->nullOnDelete();
            $table->timestamps();

            $table->index(['socio_id', 'estado']);
            $table->index(['gimnasio_id', 'fin']);
        });

        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
            $table->foreignId('membresia_id')->nullable()->constrained('membresias')->nullOnDelete();
            $table->decimal('monto', 10, 2);
            $table->enum('metodo', ['efectivo', 'transferencia', 'mercadopago', 'tarjeta', 'otro'])->default('efectivo');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado', 'reembolsado'])->default('pendiente');
            $table->string('mp_payment_id')->nullable()->comment('ID de pago en Mercado Pago');
            $table->string('mp_status')->nullable();
            $table->string('comprobante_path')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('registrado_por')->nullable()->constrained('gym_users')->nullOnDelete();
            $table->timestamp('pagado_at')->nullable();
            $table->timestamps();

            $table->index(['gimnasio_id', 'estado']);
            $table->index(['socio_id', 'estado']);
            $table->index('mp_payment_id');
        });

        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
            $table->enum('metodo_registro', ['manual', 'qr'])->default('manual');
            $table->foreignId('registrado_por')->nullable()->constrained('gym_users')->nullOnDelete()
                  ->comment('Null si fue por QR');
            $table->timestamp('ingreso');
            $table->timestamps();

            $table->index(['gimnasio_id', 'ingreso']);
            $table->index(['socio_id', 'ingreso']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('membresias');
        Schema::dropIfExists('planes_membresia');
        Schema::dropIfExists('member_users');
        Schema::dropIfExists('socios');
    }
};

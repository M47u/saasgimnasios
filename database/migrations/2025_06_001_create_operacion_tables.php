<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->foreignId('entrenador_id')->nullable()->constrained('gym_users')->nullOnDelete();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->unsignedTinyInteger('cupo_maximo')->default(20);
            $table->unsignedTinyInteger('duracion_min')->default(60);
            $table->time('hora_inicio');
            $table->enum('recurrencia', ['unica', 'diaria', 'semanal'])->default('semanal');
            $table->json('dias_semana')->nullable()->comment('Ej: ["lunes","miercoles","viernes"]');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('gimnasio_id');
        });

        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clase_id')->constrained('clases')->cascadeOnDelete();
            $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->date('fecha');
            $table->enum('estado', ['reservada', 'asistio', 'ausente', 'cancelada'])->default('reservada');
            $table->timestamps();

            $table->unique(['clase_id', 'socio_id', 'fecha']);
            $table->index(['gimnasio_id', 'fecha']);
        });

        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('gym_users')->restrictOnDelete();
            $table->decimal('monto_apertura', 10, 2)->default(0);
            $table->decimal('monto_cierre', 10, 2)->nullable();
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');
            $table->timestamp('apertura');
            $table->timestamp('cierre')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['gimnasio_id', 'estado']);
        });

        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained('cajas')->cascadeOnDelete();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->enum('tipo', ['ingreso', 'egreso'])->default('ingreso');
            $table->decimal('monto', 10, 2);
            $table->string('concepto');
            $table->unsignedBigInteger('referencia_id')->nullable()->comment('ID del pago o venta origen');
            $table->string('referencia_tipo')->nullable()->comment('Pago | Venta | Manual');
            $table->foreignId('registrado_por')->nullable()->constrained('gym_users')->nullOnDelete();
            $table->timestamps();

            $table->index('caja_id');
            $table->index('gimnasio_id');
        });

        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('categoria')->nullable()->comment('Ej: suplemento, indumentaria, bebida');
            $table->decimal('precio', 10, 2);
            $table->unsignedSmallInteger('stock')->default(0);
            $table->unsignedSmallInteger('stock_minimo')->default(5)->comment('Umbral para alerta de stock bajo');
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['gimnasio_id', 'activo']);
        });

        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
            $table->foreignId('socio_id')->nullable()->constrained('socios')->nullOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('gym_users')->nullOnDelete();
            $table->foreignId('caja_id')->nullable()->constrained('cajas')->nullOnDelete();
            $table->unsignedSmallInteger('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('total', 10, 2);
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'mercadopago', 'tarjeta', 'otro'])->default('efectivo');
            $table->timestamps();

            $table->index(['gimnasio_id', 'created_at']);
        });

        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
            $table->enum('tipo', [
                'membresia_por_vencer',
                'membresia_vencida',
                'rutina_asignada',
                'plan_nutricional_activado',
                'feedback_revisado',
                'logro_desbloqueado',
                'clase_proxima',
                'pago_confirmado'
            ]);
            $table->string('titulo');
            $table->text('mensaje');
            $table->boolean('leida')->default(false);
            $table->boolean('email_enviado')->default(false);
            $table->timestamp('enviada_at')->nullable();
            $table->timestamps();

            $table->index(['socio_id', 'leida']);
            $table->index('gimnasio_id');
        });

        Schema::create('ia_conversaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimnasio_id')->constrained('gimnasios')->cascadeOnDelete();
            $table->foreignId('socio_id')->nullable()->constrained('socios')->nullOnDelete();
            $table->foreignId('gym_user_id')->nullable()->constrained('gym_users')->nullOnDelete();
            $table->enum('contexto', ['socio', 'entrenador'])->default('socio');
            $table->json('mensajes')->comment('Array de {role, content}');
            $table->unsignedInteger('tokens_usados')->default(0);
            $table->timestamps();

            $table->index(['gimnasio_id', 'created_at']);
            $table->index('socio_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ia_conversaciones');
        Schema::dropIfExists('notificaciones');
        Schema::dropIfExists('ventas');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('movimientos_caja');
        Schema::dropIfExists('cajas');
        Schema::dropIfExists('reservas');
        Schema::dropIfExists('clases');
    }
};

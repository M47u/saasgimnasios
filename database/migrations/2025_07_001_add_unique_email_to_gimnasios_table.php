<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Nota: La validación de email único (excluyendo cancelados) se implementa 
        // completamente en la aplicación usando Rule::unique() en SaasGimnasioController.
        // 
        // Esta migración está vacía porque:
        // - MySQL < 8.0.13 no soporta índices únicos parciales con WHERE
        // - La validación en la aplicación es suficiente y más flexible
        // - Permite reutilizar emails de gimnasios eliminados (cancelados)
    }

    public function down(): void
    {
        // No hay cambios que revertir
    }
};

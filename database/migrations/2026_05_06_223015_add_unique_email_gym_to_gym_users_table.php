<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gym_users', function (Blueprint $table) {
            // Agregar índice único compuesto en (email, gimnasio_id)
            $table->unique(['email', 'gimnasio_id'], 'unique_email_per_gym');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gym_users', function (Blueprint $table) {
            $table->dropUnique('unique_email_per_gym');
        });
    }
};

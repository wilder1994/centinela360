<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregar la columna company_id como clave foránea
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');

            // Agregar las columnas adicionales
            $table->string('phone', 20)->nullable();
            $table->boolean('active')->default(true);
            $table->string('photo')->nullable(); // Foto de perfil
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar las columnas y la clave foránea
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'phone', 'active', 'photo']);
        });
    }
};

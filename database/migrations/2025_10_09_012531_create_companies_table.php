<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            // Información general
            $table->string('name');                                // Nombre de la empresa
            $table->string('nit')->unique();                       // NIT o identificación
            $table->string('address')->nullable();                 // Dirección
            $table->string('phone', 20)->nullable();               // Teléfono
            $table->string('email')->unique();                     // Correo principal
            $table->string('representative')->nullable();          // Representante legal o responsable

            // Identidad visual y branding
            $table->string('logo')->nullable();                    // Logo (storage/app/public/companies/)
            $table->string('color_primary', 20)->default('#007bff');
            $table->string('color_secondary', 20)->default('#002b55');
            $table->string('color_text', 20)->default('#ffffff');

            // Configuración general
            $table->boolean('active')->default(true);              // Estado de la empresa
            $table->enum('status', ['active','suspended','inactive'])->default('active');
            $table->date('subscription_expires_at')->nullable();   // Fecha de vencimiento
            $table->text('notes')->nullable();                     // Notas internas

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

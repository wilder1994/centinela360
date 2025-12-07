<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('position')->nullable();
            $table->string('document_type')->nullable();
            $table->string('document_number')->nullable();
            $table->string('rh')->nullable();
            $table->string('address')->nullable();
            $table->string('service_type')->nullable();
            $table->string('status')->default('Activo');
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('notes')->nullable();
            $table->string('photo_path')->nullable();

            $table->date('birth_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('badge_expires_at')->nullable();
            $table->timestamp('archived_at')->nullable();

            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();

            $table->index(['company_id', 'last_name']);
            $table->index(['company_id', 'archived_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('document_type')->nullable();
            $table->string('document_number')->nullable();
            $table->string('rh')->nullable();
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('badge_expires_at')->nullable();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('service_type')->nullable();
            $table->string('status')->default('Activo');
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropConstrainedForeignId('client_id');
            $table->dropColumn([
                'document_type',
                'document_number',
                'rh',
                'address',
                'birth_date',
                'start_date',
                'badge_expires_at',
                'service_type',
                'status',
                'emergency_contact_name',
                'emergency_contact_phone',
                'notes',
            ]);
        });
    }
};

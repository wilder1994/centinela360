<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memorandums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('users');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('title');
            $table->string('puesto')->nullable();
            $table->text('body')->nullable();
            $table->enum('estado', ['pending', 'en_proceso', 'finalizado'])->default('pending')->index();
            $table->enum('prioridad', ['urgente', 'alta', 'media', 'baja'])->default('media')->index();
            $table->timestamp('vence_en')->nullable();
            $table->timestamps();

            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['company_id', 'estado']);
            $table->index(['company_id', 'prioridad']);
            $table->index(['company_id', 'puesto']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memorandums');
    }
};

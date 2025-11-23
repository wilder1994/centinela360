<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('service_type');
            $table->string('service_schedule');
            $table->timestamps();

            $table->index(['client_id', 'service_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_services');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('business_name');
            $table->string('nit');
            $table->string('address')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->unsignedTinyInteger('service_count')->default(1);
            $table->string('email');
            $table->string('representative_name')->nullable();
            $table->string('quadrant')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'nit']);
            $table->index(['company_id', 'business_name']);
            $table->index(['company_id', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};

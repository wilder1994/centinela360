<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memorandum_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memorandum_id')->constrained('memoranda')->cascadeOnDelete();
            $table->string('from_status', 50)->nullable();
            $table->string('to_status', 50);
            $table->foreignId('changed_by')->constrained('users')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['memorandum_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memorandum_status_histories');
    }
};

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

            // 👇 Nombre CORRECTO de la tabla: memoranda
            $table->foreignId('memorandum_id')
                ->constrained('memoranda')
                ->cascadeOnDelete();

            // 👇 Valores que coinciden con App\Enums\MemorandumStatus
            $table->enum('from_status', ['draft', 'in_review', 'acknowledged', 'archived'])->nullable();
            $table->enum('to_status', ['draft', 'in_review', 'acknowledged', 'archived']);

            $table->foreignId('changed_by')
                ->constrained('users')
                ->cascadeOnDelete();

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

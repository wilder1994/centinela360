<?php

use App\Enums\MemorandumStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('memorandums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('subject');
            $table->text('body');
            $table->string('status', 50)->default(MemorandumStatus::DRAFT->value);
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memorandums');
    }
};

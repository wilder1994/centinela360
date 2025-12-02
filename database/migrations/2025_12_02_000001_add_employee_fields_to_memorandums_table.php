<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memorandums', function (Blueprint $table) {
            $table->string('employee_name')->nullable()->after('puesto');
            $table->string('employee_document')->nullable()->after('employee_name');
            $table->string('employee_position')->nullable()->after('employee_document');
        });
    }

    public function down(): void
    {
        Schema::table('memorandums', function (Blueprint $table) {
            $table->dropColumn(['employee_name', 'employee_document', 'employee_position']);
        });
    }
};

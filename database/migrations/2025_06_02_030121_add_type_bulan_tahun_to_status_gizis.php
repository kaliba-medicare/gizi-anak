<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('status_gizis', function (Blueprint $table) {
            $table->foreignId('type_id')->constrained('types')->onDelete('cascade');
            $table->string('month');
            $table->string('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('status_gizis', function (Blueprint $table) {
            $table->dropColumn('type_id');
            $table->dropColumn('month');
            $table->dropColumn('year');
        });
    }
};

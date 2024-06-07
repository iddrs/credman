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
        Schema::table('decretos', function (Blueprint $table) {
            $table->string('tipo_decreto', length: 1)->required()->default('D');
            $table->integer('nr', unsigned: true)->required()->min(1)->unique(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('decretos', function (Blueprint $table) {
            $table->dropColumn('tipo_decreto');
        });
    }
};

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
        Schema::table('leis', function (Blueprint $table) {
            $table->decimal('bc_limite_leg', 12, 2)->required()->min(0.00)->default(0.00);
            $table->renameColumn('bc_limite', 'bc_limite_exec');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leis', function (Blueprint $table) {
            $table->dropColumn('bc_limite_leg');
            $table->renameColumn('bc_limite_exec', 'bc_limite');
        });
    }
};

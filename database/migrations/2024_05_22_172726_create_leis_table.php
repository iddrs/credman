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
        Schema::create('leis', function (Blueprint $table) {
            $table->id();
            $table->integer('nr', unsigned: true)->required()->min(1)->unique();
            $table->date('data')->required();
            $table->year('exercicio')->required()->min(1964);
            $table->decimal('bc_limite', 12, 2)->required()->min(0.00);
            $table->string('tipo')->required();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leis');
    }
};

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
        Schema::create('creditos', function (Blueprint $table) {
            $table->id();
            $table->integer('acesso')->required()->min(0);
            $table->integer('tipo')->required();
            $table->integer('origem')->required();
            $table->decimal('valor', 12, 2)->required()->min(0.01);
            $table->foreignId('decreto_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rubrica_id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creditos');
    }
};

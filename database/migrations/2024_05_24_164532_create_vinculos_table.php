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
        Schema::create('vinculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credito_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reducao_id')->nullable(true)->cascadeOnDelete();
            $table->foreignId('excesso_id')->nullable(true)->cascadeOnDelete();
            $table->foreignId('superavit_id')->nullable(true)->cascadeOnDelete();
            $table->decimal('valor', 12, 2)->required()->min(0.01);
            $table->boolean('limite')->default(false);
            $table->string('aviso')->nullable(true);
            $table->text('justificativa')->nullable(true);
            $table->foreignId('decreto_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vinculos');
    }
};

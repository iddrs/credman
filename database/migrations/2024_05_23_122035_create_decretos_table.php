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
        Schema::create('decretos', function (Blueprint $table) {
            $table->id();
            $table->integer('nr', unsigned: true)->required()->min(1)->unique();
            $table->date('data')->required();
            $table->decimal('vl_credito', 12, 2)->default(0.00)->min(0.00);
            $table->decimal('vl_reducao', 12, 2)->default(0.00)->min(0.00);
            $table->decimal('vl_superavit', 12, 2)->default(0.00)->min(0.00);
            $table->decimal('vl_excesso', 12, 2)->default(0.00)->min(0.00);
            $table->decimal('vl_reaberto', 12, 2)->default(0.00)->min(0.00);
            $table->boolean('fechado')->required()->default(false);
            $table->foreignId('lei_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decretos');
    }
};

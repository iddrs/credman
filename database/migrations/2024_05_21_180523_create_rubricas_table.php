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
        Schema::create('rubricas', function (Blueprint $table) {
            $table->id();
            $table->year('exercicio')->required()->min(2024);
            $table->integer('acesso', unsigned: true)->required()->min(1);
            $table->string('uniorcam', 4)->required();
            $table->integer('projativ', unsigned: true)->required()->min(1);
            $table->string('despesa', 6)->required();
            $table->string('fonte', 5)->required();
            $table->string('complemento', 4)->nullable(true);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubricas');
    }
};

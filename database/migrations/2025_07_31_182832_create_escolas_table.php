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
        Schema::create('escolas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nome');
            $table->string('provincia');
            $table->string('municipio')->nullable();
            $table->string('telefone')->nullable();

            // Totais
            $table->unsignedInteger('total_alunos')->default(0);
            $table->unsignedInteger('total_professores')->default(0);
            $table->unsignedInteger('total_cursos')->default(0);
            $table->unsignedInteger('total_classes')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escolas');
    }
};

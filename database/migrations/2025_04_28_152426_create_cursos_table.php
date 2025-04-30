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
        Schema::create('cursos', function (Blueprint $table) {
            $table->id(); // ID auto-incremento
            $table->string('nome'); // Nome do curso [cite: 13]
            $table->text('descricao')->nullable(); // Descrição [cite: 13] (nullable se puder ser vazio)
            $table->decimal('valor', 8, 2); // Valor [cite: 13] (Ex: 999999.99)
            $table->date('data_inicio'); // Data de início das inscrições [cite: 13]
            $table->date('data_fim'); // Data de término das inscrições [cite: 13]
            $table->integer('quantidade_maxima_inscritos')->unsigned(); // Quantidade máxima [cite: 13] (unsigned = não negativo)
            $table->string('arquivo_material')->nullable(); // Caminho para o arquivo de material [cite: 13] (nullable se for opcional)
            $table->timestamps(); // Cria colunas created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};

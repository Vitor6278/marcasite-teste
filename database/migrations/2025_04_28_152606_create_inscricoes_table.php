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
        Schema::create('inscricoes', function (Blueprint $table) {
            $table->id(); // ID da inscrição

            // Chave estrangeira para curso
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');

            // Chave estrangeira para usuário (DESCOMENTADA)
            $table->foreignId('user_id')      // Cria a coluna user_id
                  ->nullable()               // Permite inscrições sem login (opcional)
                  ->constrained('users')       // Liga à tabela 'users'
                  ->onDelete('set null');     // Se usuário for deletado, user_id vira NULL

            // Dados do Aluno
            $table->string('nome_aluno');
            $table->string('email_aluno');
            $table->string('cpf', 14)->nullable();
            $table->string('endereco')->nullable();
            $table->string('empresa')->nullable();
            $table->string('telefone_aluno', 20)->nullable();
            $table->string('celular', 20)->nullable(); // PDF pede celular, tornar obrigatório?

            // Categoria e Status
            $table->enum('categoria', ['Estudante', 'Profissional', 'Associado']);
            $table->enum('status', ['Pendente', 'Pago', 'Cancelado'])->default('Pendente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscricoes');
    }
};

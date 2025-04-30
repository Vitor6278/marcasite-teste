<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Importar o Model User
use Illuminate\Support\Facades\Hash; // Importar Hash para a senha

class AdminUserSeeder extends Seeder
{
    /**
     * Executa os seeds do banco de dados.
     */
    public function run(): void
    {
        // Cria o usuário administrador padrão
        // Usa firstOrCreate para evitar criar duplicados se o seeder for executado mais de uma vez
        User::firstOrCreate(
            ['email' => 'admin@email.com'], // Chave única para verificar se já existe
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'), // Define uma senha padrão (ex: 'password')
                'tipo_usuario' => 'Admin', // Define o tipo como Admin
                'email_verified_at' => now(), // Opcional: marca o email como verificado
            ]
        );

    }
}

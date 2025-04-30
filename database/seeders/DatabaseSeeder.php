<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Propaga o banco de dados da aplicação.
     */
    public function run(): void
    {
        // Comente ou remova a criação de usuários via factory se não precisar mais dela
        // User::factory(10)->create();

        // Comente ou remova esta linha também, pois o AdminUserSeeder cuidará disso
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // >>> ADICIONE ESTA CHAMADA AQUI <<<
        // Chama o seeder que cria o usuário admin (e outros que você adicionar lá)
        $this->call([
            AdminUserSeeder::class,
            // Se criar outros seeders (ex: CursoSeeder), adicione-os aqui:
            // OutroSeeder::class,
        ]);
    }
}

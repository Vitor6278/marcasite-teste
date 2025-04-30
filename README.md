# Marcasite Cursos - Sistema de Inscrições

Este é um sistema de gerenciamento de inscrições para cursos desenvolvido como parte de um teste de avaliação, seguindo as especificações fornecidas pela Marcasite.

## Descrição

O sistema permite:
* Administradores cadastrarem e gerenciarem cursos (nome, descrição, valor, datas, limite de inscritos, material de apoio).
* Usuários (alunos, profissionais, associados) visualizarem os cursos disponíveis.
* Usuários se registrarem no sistema (com tipo de usuário: Aluno ou Admin).
* Usuários realizarem inscrições nos cursos.
* Administradores visualizarem, editarem e excluírem as inscrições realizadas.
* Administradores filtrarem a lista de inscrições (por busca, status, categoria, período).
* Administradores exportarem a lista de inscrições para PDF e Excel (XLSX).

**Funcionalidades NÃO implementadas:**
* Integração com gateway de pagamento.
* Diferenciação automática de valores por tipo de usuário na inscrição.

## Tecnologias Utilizadas

* **Linguagem:** PHP 8.2+
* **Framework:** Laravel 11+
* **Banco de Dados:** MySQL
* **Front-end:** Bootstrap 5 (para layout principal e formulários), Blade (templates)
* **JavaScript:** Vanilla JS (para máscaras de input com IMask.js)
* **Dependências PHP:**
    * `laravel/framework`
    * `laravel/breeze` (ou Jetstream - para autenticação)
    * `barryvdh/laravel-dompdf` (para exportação PDF)
    * `phpoffice/phpspreadsheet` (para exportação Excel)
* **Gerenciador de Dependências:** Composer, NPM

## Requisitos de Ambiente

* PHP >= 8.2
* Composer
* Node.js e NPM
* Servidor Web (Apache/Nginx com PHP configurado) ou `php artisan serve`
* Banco de Dados MySQL
* Extensões PHP habilitadas: `BCMath`, `Ctype`, `cURL`, `DOM`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PCRE`, `PDO`, `PDO_MySQL`, `Tokenizer`, `XML`, `Zip`, `GD`, `iconv`, `SimpleXML`, `xmlreader`, `xmlwriter`, `zlib`, `intl`.

## Instalação e Configuração

1.  **Clone o Repositório:**
    ```bash
    git clone [URL_DO_SEU_REPOSITORIO_AQUI] marcasite-cursos
    cd marcasite-cursos
    ```

2.  **Instale Dependências PHP:**
    ```bash
    composer install
    ```

3.  **Instale Dependências JavaScript:**
    ```bash
    npm install
    ```

4.  **Configure o Ambiente:**
    * Copie `.env.example` para `.env`: `cp .env.example .env`
    * Gere a chave da aplicação: `php artisan key:generate`
    * Edite o arquivo `.env` e configure as variáveis do banco de dados (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`). Crie o banco de dados no MySQL se ainda não existir.
    * Certifique-se que `APP_DEBUG=true` e `APP_ENV=local`.

5.  **Execute Migrations e Seeders:**
    * Este comando criará as tabelas e o usuário administrador padrão (`admin@email.com` / `password`).
    ```bash
    php artisan migrate:fresh --seed
    ```

6.  **Crie o Link Simbólico para Storage:**
    ```bash
    php artisan storage:link
    ```

7.  **Compile Assets:**
    ```bash
    npm run dev
    ```
    *(Mantenha este comando rodando em um terminal separado durante o uso)*

8.  **Inicie o Servidor:**
    * Em outro terminal:
    ```bash
    php artisan serve
    ```

9.  **Acesse:** Abra o navegador em `http://127.0.0.1:8000` (ou o endereço fornecido).

## Como Usar

* **Acesso Público:** Navegue pela página inicial (`/`) para ver os cursos. Clique em "Inscrever-se" para acessar o formulário de inscrição. Use os links "Login" e "Registrar" no topo.
* **Registro:** Na página `/register`, preencha os dados e selecione o "Tipo de Usuário" (Aluno ou Administrador).
* **Login:** Use as credenciais criadas no registro ou o usuário admin padrão:
    * **Email:** `admin@email.com`
    * **Senha:** `password`
* **Área do Usuário Logado:** Após o login, você é redirecionado para a home (`/`). O menu no topo direito permite acessar o Perfil (`/profile`) e Sair.
* **Área Administrativa (Requer Login como Admin):**
    * O menu "Admin" aparecerá na barra de navegação.
    * **Adicionar Curso:** Link no menu Admin. Leva para `/admin/cursos/create`.
    * **Gerenciar Cursos:** Link no menu Admin (leva para `/admin/cursos`). Nesta página você pode ver a lista, editar ou excluir cursos.
    * **Gerenciar Inscrições:** Link no menu Admin (leva para `/admin/inscricoes`). Nesta página você pode ver a lista, filtrar (busca, status, categoria, período), editar, excluir, e exportar para PDF ou XLS.

## Estrutura do Projeto (Principais Pastas)

* `app/Http/Controllers/`: Contém os controllers (CursoController, InscricaoController, Auth controllers, etc.).
* `app/Models/`: Contém os Models Eloquent (User, Curso, Inscricao).
* `app/Providers/`: Contém os Service Providers (incluindo os recriados Auth, Event, Route).
* `app/Exports/`: Contém a classe `InscricoesExport` usada para gerar o Excel.
* `config/`: Arquivos de configuração (app.php, database.php, etc.).
* `database/migrations/`: Arquivos de migration para criar a estrutura do banco.
* `database/seeders/`: Arquivos Seeder (DatabaseSeeder, AdminUserSeeder).
* `public/`: Pasta pública, ponto de entrada da aplicação (contém `index.php`, assets compilados, storage link).
* `resources/css/`: Arquivos CSS (app.css).
* `resources/js/`: Arquivos JavaScript (app.js, bootstrap.js).
* `resources/views/`: Arquivos Blade para as views (HTML).
    * `auth/`: Views de autenticação.
    * `cursos/`: Views públicas de cursos.
    * `inscricoes/`: Views públicas de inscrição.
    * `layouts/`: Layouts base (app.blade.php, guest.blade.php, navigation.blade.php).
    * `admin/`: Views da área administrativa.
        * `cursos/`: Views de gerenciamento de cursos.
        * `inscricoes/`: Views de gerenciamento de inscrições (incluindo `pdf.blade.php`).
* `routes/`: Arquivos de definição de rotas (web.php, auth.php, api.php, console.php).
* `storage/app/public/`: Onde os arquivos de material dos cursos são armazenados.

Espero que este sistema e sua documentação atendam aos critérios da avaliação, apresentando de forma clara as funcionalidades desenvolvidas com o Laravel.
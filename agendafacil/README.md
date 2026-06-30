# AgendaFácil

Sistema de agendamento online desenvolvido em Laravel com apoio de Laravel Boost e desenvolvimento assistido por IA (Vibe Coding). Permite que clientes escolham um serviço, vejam horários livres de um profissional, agendem, consultem e cancelem reservas; e que administradores acompanhem a agenda e gerenciem o catálogo de serviços.

## Descrição da aplicação

O AgendaFácil resolve a dificuldade de marcar horários em estabelecimentos de serviço sem depender de telefone ou mensagens. O sistema mostra apenas horários realmente livres e impede sobreposição de agendamentos para o mesmo profissional. Há dois perfis: **cliente** (agenda e gerencia os próprios horários) e **administrador** (vê toda a agenda e mantém os serviços).

Principais funcionalidades:

- Cadastro e login de usuários.
- Listagem de serviços ativos.
- Consulta de horários livres por serviço, profissional e data.
- Criação de agendamento com bloqueio de horário passado e de conflito.
- Listagem e cancelamento dos próprios agendamentos.
- Painel administrativo: agenda completa com filtro por status e CRUD de serviços.

## Tecnologias utilizadas

- **Laravel** (PHP) — framework principal.
- **Laravel Boost** — desenvolvimento assistido por IA (MCP + Skills + guidelines).
- **Blade** — camada de views.
- **CSS próprio com tokens** — identidade visual sem framework de UI (`public/css/app.css`).
- **MySQL** em desenvolvimento; **SQLite em memória** nos testes.
- **PHPUnit** — testes unitários e de feature.

## Requisitos

- PHP 8.2+
- Composer
- MySQL (ou outro banco suportado pelo Laravel)
- Node.js (opcional — apenas se for compilar assets; o CSS aqui é servido direto de `public/`)

## Instalação

### 1. Clonar e instalar dependências

```bash
git clone <url-do-repositorio>
cd agendafacil
composer install
```

### 2. Configurar o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Edite o `.env` com as credenciais do banco:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agendafacil
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Criar o banco, rodar migrations e seeders

Crie um banco vazio com o nome definido em `DB_DATABASE` e então:

```bash
php artisan migrate --seed
```

Isso cria todas as tabelas e popula o sistema com usuários de teste, profissionais, serviços e disponibilidades.

### 4. Executar o projeto

```bash
php artisan serve
```

A aplicação ficará disponível em `http://127.0.0.1:8000`.

## Usuários de teste

Cadastrados automaticamente via Seeders (`database/seeders/UserSeeder.php`):

| Perfil | E-mail | Senha |
|--------|--------|-------|
| Administrador | `admin@agenda.test` | `password` |
| Cliente | `cliente@agenda.test` | `password` |
| Cliente | `maria@agenda.test` | `password` |

O administrador é redirecionado para a agenda administrativa após o login; os clientes, para a lista de serviços.

## Testes

```bash
php artisan test
```

A suíte cobre as regras de negócio (cálculo de horário, bloqueio de passado e de conflito, geração de slots), o isolamento de dados entre usuários, o CRUD de serviços e a autenticação.

## Estrutura do repositório

```
agendafacil/
├── README.md
├── RELATORIO.md
├── .ai/
│   └── skills/            # Skills que orientam a IA (ver RELATORIO.md)
├── app/
│   ├── Http/Controllers/  # Auth, Service, Appointment, Admin
│   ├── Http/Requests/     # Form Requests (validação)
│   ├── Http/Middleware/   # EnsureUserIsAdmin
│   ├── Models/            # User, Service, Professional, Availability, Appointment
│   ├── Policies/          # AppointmentPolicy
│   └── Services/          # AppointmentService (regra de negócio)
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── docs/
│   └── PLANO_DE_IMPLEMENTACAO.md
├── public/css/app.css     # Identidade visual (tokens)
├── resources/views/       # Blade
├── routes/
└── tests/                 # Unit e Feature
```

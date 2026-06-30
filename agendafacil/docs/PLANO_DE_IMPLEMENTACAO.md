# Plano de Implementação — AgendaFácil

> Elaborado antes da geração de código com IA, conforme a Parte 5 da atividade.

## 1. Contexto

### Objetivo da aplicação

Permitir que um usuário escolha um serviço, veja os horários livres de um profissional, realize um agendamento, consulte seus agendamentos e cancele reservas futuras. Um administrador acompanha toda a agenda e gerencia os serviços oferecidos.

### Problema que resolve

Os clientes de estabelecimentos de serviço (clínicas, salões, consultórios) enfrentam dificuldade para marcar horários, dependendo de telefone ou mensagens e ficando sujeitos a conflitos de agenda e remarcações. Isso gera lentidão no atendimento e erros de marcação. Para resolver, o AgendaFácil oferece um fluxo de autoatendimento que mostra apenas horários realmente livres e impede sobreposição de agendamentos.

### Público-alvo

Clientes que querem agendar serviços de forma autônoma e administradores do estabelecimento que precisam visualizar a agenda e manter o catálogo de serviços atualizado.

## 2. Escopo

### Funcionalidades (MVP)

- Cadastro e login de usuário.
- Listagem de serviços disponíveis.
- Consulta de horários livres por serviço, profissional e data.
- Criação de agendamento, com bloqueio de horário passado e de conflito.
- Listagem dos próprios agendamentos.
- Cancelamento de agendamento futuro.
- Painel administrativo: agenda completa (com filtro por status) e CRUD de serviços.

Fora do escopo desta versão: pagamento online, notificações por e-mail/WhatsApp e integração com Google Calendar.

### Entidades do banco

- **User** — `id, name, email, password, phone, role` (admin ou cliente).
- **Service** — `id, name, description, duration_minutes, price, active`.
- **Professional** — `id, name, email, active`.
- **Availability** — `id, professional_id, weekday, start_time, end_time` (janelas de trabalho por dia da semana).
- **Appointment** — `id, user_id, service_id, professional_id, start_at, end_at, status`.

### Telas

Login, registro, lista de serviços, detalhe do serviço (escolha de profissional e data), horários livres, meus agendamentos, agenda administrativa, e CRUD de serviços (lista, criação, edição).

### Ordem de implementação

1. Configuração do projeto e do Laravel Boost.
2. Skills (Identidade Visual, CRUD, Segurança, Testes).
3. Plano de implementação (este documento).
4. Migrations e modelagem das entidades.
5. Autenticação.
6. CRUD de serviços (admin).
7. Fluxo de agendamento (slots, criação, listagem, cancelamento).
8. Agenda administrativa.
9. Seeders e usuários de teste.
10. Testes automatizados.
11. README e relatório.

## 3. Técnico

### Tecnologias utilizadas

- **Laravel** (PHP) como framework principal.
- **Laravel Boost** para o desenvolvimento assistido por IA (MCP + Skills).
- **Blade** para as views.
- **CSS próprio** com sistema de tokens (sem framework de UI), seguindo a Skill de Identidade Visual.
- **MySQL** em desenvolvimento; **SQLite em memória** nos testes.
- **PHPUnit** para testes unitários e de feature.

### Riscos

- **Conflito de horário**: risco central do domínio. Mitigado por uma regra única de detecção de sobreposição (`Appointment::scopeConflicting`) usada tanto na geração de slots quanto na criação, com índice no banco para desempenho.
- **Acesso indevido a dados de outro usuário**: mitigado por Policy e por consultas que partem sempre do usuário autenticado.
- **Entrada inválida**: mitigada por Form Requests com regras explícitas.
- **Código gerado por IA sem revisão**: mitigado pela revisão manual e pela suíte de testes que cobre as regras críticas.

### Critérios de aceite

- O usuário consegue realizar um agendamento completo.
- O sistema bloqueia horários já ocupados e horários no passado.
- O usuário visualiza e cancela apenas os próprios agendamentos.
- O administrador visualiza toda a agenda e gerencia os serviços.
- O sistema exibe mensagens claras de sucesso e erro.
- A suíte de testes automatizados passa integralmente.

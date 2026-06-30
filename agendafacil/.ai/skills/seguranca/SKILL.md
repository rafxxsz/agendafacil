# Skill: Segurança

## Objetivo

Orientar a IA a aplicar consistentemente as proteções de segurança do AgendaFácil: autenticação, autorização, isolamento de dados entre usuários e validação de entrada. O princípio central é que nenhum usuário pode ver ou alterar dados de outro, e nenhuma ação sensível ocorre sem verificação de permissão.

## Quando aplicar

Em qualquer rota, controller ou consulta que envolva dados de usuário, áreas administrativas ou entrada vinda do cliente.

## Autenticação

- Rotas que exigem login ficam no grupo `Route::middleware('auth')`.
- Rotas de visitante (login, registro) ficam no grupo `guest`.
- Após login e registro, regenerar a sessão (`$request->session()->regenerate()`); no logout, invalidar e regenerar o token.
- Senhas sempre via cast `'hashed'` no model `User` — nunca gravar senha em texto puro.

## Autorização

- Área administrativa protegida pelo middleware `admin` (`EnsureUserIsAdmin`), que aborta com 403 quando o usuário não é admin.
- Ações sobre um registro específico usam Policy. Exemplo: `AppointmentPolicy` decide quem pode ver e cancelar um agendamento.
- No controller, chamar `$this->authorize('cancel', $appointment)` antes de alterar. Nunca confiar apenas no fato de a rota estar autenticada.

## Isolamento de dados (o ponto mais crítico)

- Consultas de dados do usuário partem sempre do usuário autenticado: `$request->user()->appointments()`, nunca `Appointment::all()` filtrado depois.
- Um usuário só lista, vê e cancela os próprios agendamentos. O teste `test_usuario_ve_apenas_os_proprios_agendamentos` cobre essa garantia.
- Cancelamento de agendamento alheio retorna 403 (coberto por `test_usuario_nao_cancela_agendamento_de_outro`).

## Validação de entrada

- Toda entrada passa por Form Request com regras explícitas.
- Chaves estrangeiras validadas com `exists` (ex.: `service_id` precisa existir).
- Regras de negócio que protegem integridade (não agendar no passado, não permitir conflito de horário) ficam em `AppointmentService` e lançam `ValidationException` com mensagem clara.
- Nunca aceitar campos que o usuário não deveria controlar (ex.: `role`, `user_id` do agendamento vêm do servidor, não do formulário).

## Proteções padrão do Laravel a manter

- Token CSRF em todo formulário (`@csrf`). Nunca desativar a verificação CSRF em rotas web.
- Eloquent (consultas parametrizadas) em vez de SQL concatenado, evitando SQL injection.
- Blade escapa saída por padrão (`{{ }}`); usar `{!! !!}` apenas com conteúdo confiável e sanitizado.
- `$fillable` definido em todos os models para evitar mass assignment de campos indevidos.

## Checklist de segurança

- [ ] Rota no grupo de middleware correto (`auth`, `admin`).
- [ ] Policy verificada antes de alterar registro específico.
- [ ] Consulta parte do usuário autenticado, não do conjunto global.
- [ ] Form Request validando toda entrada, com `exists` em FKs.
- [ ] Campos sensíveis definidos no servidor, fora do formulário.
- [ ] `@csrf` presente em todo formulário.
- [ ] Teste cobrindo o bloqueio de acesso indevido.

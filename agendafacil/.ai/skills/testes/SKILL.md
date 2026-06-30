# Skill: Testes

## Objetivo

Orientar a IA a escrever testes automatizados consistentes para o AgendaFácil, cobrindo tanto regras de negócio isoladas (testes unitários) quanto fluxos completos pela aplicação (testes de feature). O objetivo é que cada regra crítica tenha um teste que falharia se a regra fosse quebrada.

## Quando aplicar

Sempre que criar ou alterar uma regra de negócio, um endpoint ou um CRUD. Toda alteração de comportamento deve vir acompanhada do teste correspondente.

## Estrutura

- **Testes unitários** (`tests/Unit`): exercitam uma classe isolada, sem passar pela camada HTTP. Caso típico: `AppointmentServiceTest`, que testa cálculo de horário final, bloqueio de horário passado e detecção de conflito.
- **Testes de feature** (`tests/Feature`): exercitam o fluxo via rota, com `actingAs($user)` e asserções sobre resposta e banco. Casos: autenticação, fluxo de agendamento, CRUD de serviços.
- Banco isolado por teste com a trait `RefreshDatabase`. A configuração usa SQLite em memória (ver `phpunit.xml`).

## Padrões a seguir

- Nome do método descreve o comportamento em português: `test_impede_conflito_de_horario_para_o_mesmo_profissional`.
- Usar factories para montar o cenário (`User::factory()`, `Service::factory()`, `Appointment::factory()`), com states quando útil (`->admin()`, `->cancelled()`, `->past()`).
- Asserções de banco com `assertDatabaseHas`, `assertDatabaseMissing`, `assertDatabaseCount`.
- Asserções de resposta com `assertRedirect`, `assertOk`, `assertForbidden`, `assertSessionHasErrors`.
- Datas de teste sempre relativas (`Carbon::tomorrow()`, `next monday`), nunca datas fixas que expiram.

## O que cobrir obrigatoriamente

Regras de negócio do agendamento:

- Cálculo correto do horário final pela duração do serviço.
- Bloqueio de agendamento em horário passado.
- Bloqueio de conflito de horário para o mesmo profissional.
- Permissão de mesmo horário para profissionais diferentes.
- Geração apenas de slots livres (descontando ocupados e passados).

Segurança e acesso:

- Usuário vê apenas os próprios agendamentos.
- Usuário não cancela agendamento de outro (403).
- Visitante é redirecionado ao login.
- Cliente não acessa área administrativa (403).

CRUD e autenticação:

- Admin cria, edita e remove serviço.
- Validação rejeita entrada inválida (ex.: preço negativo).
- Registro, login, login com senha errada e logout.

## Como rodar

```bash
php artisan test
# ou um arquivo específico
php artisan test --filter=AppointmentServiceTest
```

## Checklist ao adicionar comportamento

- [ ] Regra de negócio nova tem teste unitário no service correspondente.
- [ ] Endpoint novo tem teste de feature cobrindo sucesso e erro.
- [ ] Restrição de acesso tem teste que verifica o bloqueio (403/redirect).
- [ ] Cenário usa factories e datas relativas.
- [ ] `php artisan test` passa inteiro antes de considerar concluído.

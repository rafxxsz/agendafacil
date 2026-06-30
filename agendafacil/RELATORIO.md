# Relatório — AgendaFácil

## 1. Contexto e Planejamento

### Tema

**Sistema de agendamento online (AgendaFácil).** Tema único confirmado com o professor antes do início da implementação.

### Descrição da aplicação

O AgendaFácil permite que clientes agendem serviços escolhendo profissional e data, vendo apenas horários livres, e que administradores acompanhem toda a agenda e gerenciem o catálogo de serviços. O problema central que resolve é a marcação manual de horários, sujeita a conflitos e lentidão; a aplicação garante que dois agendamentos não ocupem o mesmo horário de um profissional e que cada usuário só acesse os próprios dados.

### Plano de Implementação

O plano completo está em `docs/PLANO_DE_IMPLEMENTACAO.md` e foi elaborado **antes** de gerar código com IA. Ele define contexto, escopo (funcionalidades, entidades, telas, ordem de implementação) e a parte técnica (tecnologias, riscos e critérios de aceite). A ordem seguida foi: configuração → skills → plano → modelagem → autenticação → CRUD → fluxo de agendamento → agenda administrativa → seeders → testes → documentação.

## 2. Ferramentas de IA

### MCP utilizado

**Laravel Boost MCP** (servidor local, executado via `php artisan boost:mcp`).

- **Qual MCP**: o servidor MCP do Laravel Boost, instalado com `composer require laravel/boost --dev` e `php artisan boost:install`. É um servidor MCP específico de Laravel, com mais de 15 ferramentas que permitem ao agente de IA inspecionar e agir dentro da aplicação.
- **Para qual finalidade**: dar ao assistente de IA contexto preciso do projeto (rotas, schema, configuração, logs e documentação da versão exata do Laravel instalada), de modo a gerar código idiomático e reduzir erros de versão. As ferramentas expostas incluem informações da aplicação, busca na documentação, Tinker, logs do navegador, consultas ao banco, schema do banco, listagem de comandos Artisan, últimos erros, listagem de rotas, leitura de valores de configuração e leitura de entradas de log.
- **Exemplos de utilização no projeto**:
  - **List Routes / Database Schema** — para conferir, durante a construção do CRUD e do fluxo de agendamento, se rotas e colunas geradas batiam com o que o controller esperava.
  - **Tinker** — para validar rapidamente regras de negócio (por exemplo, instanciar o `AppointmentService` e checar o cálculo do horário final e a detecção de conflito) sem precisar montar uma tela.
  - **Search Docs** — para consultar a documentação da versão instalada do Laravel ao decidir entre `Route::resource` e rotas individuais e ao configurar Policies. A ferramenta Search Docs consulta a API de documentação hospedada do Laravel para recuperar documentação baseada nos pacotes instalados.

Observação de boas práticas adotada: independentemente de quão bom o resultado pareça, todo código gerado foi tratado como rascunho — com execução dos testes, revisão dos diffs e revisão de código como parte não-negociável do processo.

### Skills desenvolvidas

As skills ficam em `.ai/skills/` e orientam a IA a seguir os padrões do projeto. Foram criadas quatro (duas obrigatórias e duas adicionais):

1. **Identidade Visual** (obrigatória) — paleta, tipografia, espaçamento, componentes padronizados, responsividade e acessibilidade. Garante que toda tela siga o mesmo sistema visual definido em `public/css/app.css`.
2. **CRUD** (obrigatória) — estrutura em camadas (rota → Form Request → controller → model → views), padrão de validação, paginação, mensagens e parcial de formulário compartilhado. O CRUD de serviços é a referência viva dessa skill.
3. **Segurança** (adicional) — autenticação, autorização por Policy, isolamento de dados entre usuários, validação de entrada e proteções padrão do Laravel (CSRF, mass assignment, escape do Blade).
4. **Testes** (adicional) — padrão de testes unitários e de feature, uso de factories e datas relativas, e a lista de comportamentos que devem obrigatoriamente ter cobertura.

> Nota: os arquivos gerados automaticamente pelo Boost (`.mcp.json`, `CLAUDE.md`, `boost.json`) são regenerados por `boost:install`/`boost:update`. As skills e guidelines próprias em `.ai/` é que constituem a contribuição autoral mantida no repositório.

## 3. Desenvolvimento

### Funcionalidades implementadas

- Autenticação completa (registro, login, logout) com perfis admin e cliente.
- Vitrine de serviços ativos com paginação.
- Fluxo de agendamento: escolha de serviço, profissional e data; geração de horários livres; confirmação.
- Listagem e cancelamento dos próprios agendamentos.
- Agenda administrativa com filtro por status.
- CRUD completo de serviços (criar, listar, editar, remover) restrito a administradores.
- Seeders com usuários de teste, profissionais, serviços e disponibilidades.
- Suíte de testes unitários e de feature.

### Decisões de projeto

- **Regra de conflito única e centralizada**: a detecção de sobreposição vive em um único lugar (`Appointment::scopeConflicting`) e é reutilizada tanto na geração de slots quanto na criação do agendamento. Isso evita divergência entre "o horário aparece como livre" e "o horário é aceito".
- **Lógica de negócio fora do controller**: a geração de slots e a regra de criação ficam em `AppointmentService`, mantendo os controllers enxutos — alinhado à Skill de CRUD e ao requisito de código em camadas.
- **Disponibilidade por dia da semana** (`Availability` com `weekday`), em vez de cadastrar cada data individualmente, simplificando o MVP e a geração de horários.
- **Autorização por Policy** (`AppointmentPolicy`) em vez de checagens espalhadas, garantindo de forma testável que ninguém cancela agendamento alheio.
- **CSS com tokens, sem framework de UI**: optou-se por um sistema próprio de variáveis para manter a identidade visual sob controle direto da skill, sem o peso e a aparência genérica de um framework pronto.

### Dificuldades encontradas

- **Sobreposição de horários**: o caso mais delicado foi garantir que a condição de conflito (`start_at < fim_novo E end_at > inicio_novo`) cobrisse todos os encaixes (início dentro, fim dentro, evento contido e evento que engloba). Resolvido com a regra única e coberto por teste específico.
- **Coerência entre slot exibido e slot aceito**: inicialmente a tela poderia oferecer um horário que a validação recusaria; unificar a regra eliminou o problema.
- **Fuso e datas em teste**: testes com datas fixas quebram com o tempo; a solução foi usar datas relativas (`Carbon::tomorrow()`, `next monday`).

## 4. Conclusão

### Limitações da aplicação

- Não há pagamento online, notificações (e-mail/WhatsApp) nem integração com Google Calendar — itens deliberadamente fora do MVP.
- A disponibilidade é semanal e não trata exceções como feriados ou bloqueios pontuais de agenda.
- Não há reagendamento direto: o usuário cancela e cria um novo.
- A agenda administrativa é de leitura e filtro; não permite editar agendamentos de clientes.

### Utilização da IA durante o desenvolvimento

A IA foi usada para gerar a primeira versão de models, migrations, controllers, Form Requests, views e testes, sempre orientada pelas skills do projeto e pelo contexto fornecido via Laravel Boost MCP. Todo código foi revisado, ajustado e testado manualmente: as skills serviram como "contrato" de padrão, e os testes automatizados como rede de segurança para validar que o comportamento gerado estava correto.

### Conclusão geral

O AgendaFácil cumpre os critérios de aceite definidos no plano: agendamento completo, bloqueio de horários ocupados e passados, isolamento de dados por usuário, gestão administrativa de serviços e mensagens claras. O uso combinado de Laravel Boost (contexto), skills (padrão) e testes (verificação) tornou o desenvolvimento assistido por IA produtivo sem abrir mão da compreensão e do controle sobre o código entregue.

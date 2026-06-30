# Skill: CRUD

## Objetivo

Garantir que todo CRUD do AgendaFácil siga o mesmo padrão de estrutura, validação, mensagens e organização de código. Quando a IA criar um novo recurso administrável, ele deve ser indistinguível, em forma, do CRUD de serviços já existente (`ServiceAdminController` + `ServiceRequest` + views em `resources/views/admin/services`).

## Quando aplicar

Sempre que for criar ou alterar operações de criar, listar, editar ou remover qualquer entidade. Antes de começar, abrir o CRUD de serviços como referência e replicar a mesma divisão de responsabilidades.

## Estrutura de camadas

A lógica é dividida em camadas; o controller nunca concentra validação nem regra de negócio:

1. **Rota** — declarada com `Route::resource(...)` dentro do grupo apropriado (com middleware `auth` e, se administrativo, `admin`). Excluir métodos não usados com `->except([...])`.
2. **Form Request** — uma classe por recurso (ex.: `ServiceRequest`) com `authorize()`, `rules()` e `messages()` em português. Validação mora aqui, não no controller.
3. **Controller** — apenas orquestra: recebe o request validado, chama o model ou service e redireciona com mensagem flash. Métodos `index`, `create`, `store`, `edit`, `update`, `destroy`.
4. **Model** — `$fillable`, `casts()` e scopes (`active`, etc.). Regra de negócio reutilizável vai para uma classe em `app/Services` quando passar de trivial.
5. **Views** — `index`, `create`, `edit` e um parcial `_form.blade.php` compartilhado entre criar e editar.

## Padrão dos controllers

- Métodos com type hints e retorno tipado (`: View`, `: RedirectResponse`).
- `store`/`update` recebem o Form Request e usam `$request->validated()`.
- Após gravar, redirecionar para o `index` com `->with('success', '...')`. Mensagens de sucesso em português, descrevendo o que aconteceu.
- `create` instancia um model vazio (`new Service()`) para o parcial de formulário funcionar igual em criação e edição.
- Usar route model binding (`Service $service`) em vez de buscar por id manualmente.

## Padrão dos formulários

- Um único parcial `_form.blade.php` incluído por `create` e `edit` via `@include`.
- Campos preenchidos com `old('campo', $model->campo)` para preservar entrada após erro de validação.
- Cada campo mostra seu erro com `@error('campo')` logo abaixo.
- `create` usa `@csrf`; `edit` usa `@csrf` + `@method('PUT')`; remoção usa `@csrf` + `@method('DELETE')`.
- Checkbox booleano normalizado no Form Request via `prepareForValidation()`.

## Padrão das listagens

- Sempre paginadas (`->paginate(10)`), com `{{ $items->links() }}` ao final.
- Cabeçalho com título, subtítulo e botão de "Novo ..." alinhado à direita (`.spacer`).
- Estado vazio com `.empty-state` e link para a ação de criação.
- Ações de editar/remover por linha; remoção com `onsubmit="return confirm(...)"`.

## Validação e mensagens

- Regras explícitas: `required`, limites de tamanho, `min`/`max` numéricos, `exists` para chaves estrangeiras.
- Nunca confiar em entrada do cliente para campos sensíveis (ex.: não aceitar preço negativo — regra já presente em `ServiceRequest`).
- Toda mensagem de erro e de sucesso em português e específica ("Informe o nome do serviço.", não "Campo obrigatório.").

## Checklist ao criar um novo CRUD

- [ ] Migration com índices e restrições necessárias.
- [ ] Model com `$fillable`, `casts()` e scopes.
- [ ] Form Request com `authorize`, `rules`, `messages`.
- [ ] Controller resource enxuto, tipado, com flash de sucesso.
- [ ] Rota resource no grupo com middleware correto.
- [ ] Views `index`/`create`/`edit` + parcial `_form` compartilhado.
- [ ] Paginação e estado vazio na listagem.
- [ ] Testes de feature cobrindo criar, editar, remover e bloqueio de acesso.

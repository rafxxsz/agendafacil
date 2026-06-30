# Skill: Identidade Visual

## Objetivo

Orientar a IA a manter consistência visual em todas as telas do AgendaFácil. Toda nova tela ou componente deve seguir a paleta, a tipografia, o espaçamento e os padrões de componente definidos aqui. O resultado esperado é uma interface coesa, legível e responsiva, sem variações arbitrárias de estilo entre páginas.

## Quando aplicar

Sempre que for criar ou alterar qualquer view Blade, componente de interface ou regra de CSS. Antes de escrever estilo novo, verificar se já existe um token ou uma classe utilitária em `public/css/app.css` que resolva o caso.

## Paleta de cores

As cores são definidas como variáveis CSS em `:root` e nunca devem ser escritas como valores hex soltos no HTML. Usar sempre a variável correspondente.

| Token | Valor | Uso |
|-------|-------|-----|
| `--color-bg` | `#f5f7f8` | Fundo geral da página |
| `--color-surface` | `#ffffff` | Cartões, cabeçalho, campos |
| `--color-ink` | `#14302f` | Texto principal |
| `--color-muted` | `#5c7472` | Texto secundário, legendas |
| `--color-border` | `#dce5e4` | Bordas e divisores |
| `--color-primary` | `#0f6e64` | Ações primárias, links |
| `--color-primary-dark` | `#0a524a` | Hover de ações primárias |
| `--color-accent` | `#e0a458` | Detalhe da marca (ponto do logo) |
| `--color-danger` | `#b23b3b` | Ações destrutivas, erros |
| `--color-success` | `#2f7d54` | Mensagens de sucesso |

A cor de acento (âmbar) é o único elemento de destaque e deve ser usada com moderação — apenas no ponto da marca e em detalhes pontuais. O peso visual fica no teal primário.

## Tipografia

Duas famílias, carregadas via Google Fonts no layout:

- **Fraunces** (`--font-display`): títulos de página, nome da marca e cabeçalhos de cartão. Pesos 500 e 600.
- **Inter** (`--font-body`): todo o corpo de texto, rótulos, botões e tabelas. Pesos 400, 500 e 600.

Escala: título de página ~2rem, cabeçalho de cartão ~1.2rem, corpo ~0.95rem, legenda ~0.85rem. Nunca usar tamanhos fora dessa escala sem necessidade.

## Espaçamento

Usar a escala de espaçamento (`--space-1` a `--space-12`, base 0.25rem). Preferir múltiplos da escala em vez de valores arbitrários. Cartões usam `--space-6` de padding interno; seções da página separadas por `--space-8` ou `--space-12`.

## Componentes padronizados

Reutilizar as classes existentes em vez de criar variações:

- **Botões**: `.btn` combinado com `.btn--primary`, `.btn--ghost` ou `.btn--danger`. Para versões compactas, adicionar `.btn--sm`. O texto do botão diz a ação exata ("Criar serviço", "Cancelar"), nunca "Enviar".
- **Cartões**: `.card` para qualquer bloco de conteúdo agrupado.
- **Formulários**: `.form-group` envolvendo `.label` + `.input`/`.select`/`.textarea`. Erros sempre com `.form-error` logo abaixo do campo.
- **Alertas**: `.alert` com `.alert--success` ou `.alert--error`, exibidos no topo do conteúdo.
- **Tabelas**: `.table` para listagens. Cabeçalhos em maiúsculas com `letter-spacing`.
- **Badges de status**: `.badge` com `.badge--agendado`, `.badge--cancelado` ou `.badge--concluido`.
- **Estado vazio**: `.empty-state` sempre que uma lista estiver vazia, com uma ação sugerida (nunca uma tela em branco).

## Responsividade e acessibilidade

- Layout fluido até 640px; abaixo disso o cabeçalho empilha e o padding reduz (ver media query em `app.css`).
- Foco de teclado visível em campos (`:focus` com outline). Não remover outline sem substituto.
- Respeitar `prefers-reduced-motion`: transições são desativadas quando o usuário pede menos movimento.
- Contraste de texto sempre suficiente: `--color-ink` sobre `--color-surface` ou `--color-bg`.

## O que evitar

- Cores hex soltas no HTML em vez de variáveis.
- Estilos inline extensos; preferir classes utilitárias (`.row`, `.stack`, `.muted`, `.spacer`).
- Novos tons fora da paleta.
- Telas vazias sem `.empty-state`.
- Texto de botão genérico ("OK", "Enviar") em vez do verbo da ação.

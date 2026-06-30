# Diretrizes do projeto AgendaFácil

Contexto para o agente de IA ao trabalhar neste repositório. Complementa as skills em `.ai/skills/`.

## O que é

Sistema de agendamento online em Laravel. Dois perfis: cliente (agenda e gerencia os próprios horários) e administrador (vê toda a agenda e mantém os serviços). A regra de domínio mais importante é **não permitir conflito de horário** para o mesmo profissional.

## Convenções

- Idioma de mensagens, rótulos e nomes de teste: **português**.
- Lógica de negócio mora em `app/Services` (ver `AppointmentService`), não nos controllers.
- A detecção de conflito é **única**: `Appointment::scopeConflicting`. Nunca duplicar essa regra; reutilizá-la na geração de slots e na criação.
- Autorização sobre registros específicos passa por Policy (`AppointmentPolicy`), nunca por checagem solta no controller.
- Toda entrada validada por Form Request.
- Identidade visual via tokens em `public/css/app.css`; não introduzir framework de UI nem cores fora da paleta.

## Antes de concluir qualquer alteração

1. Seguir a skill correspondente (CRUD, Identidade Visual, Segurança, Testes).
2. Escrever/atualizar o teste que cobre o comportamento.
3. Rodar `php artisan test` e garantir que passa.
4. Tratar todo código gerado como rascunho: revisar o diff antes de aceitar.

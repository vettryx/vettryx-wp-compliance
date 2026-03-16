# VETTRYX WP Compliance

> ⚠️ **Atenção:** Este repositório agora atua exclusivamente como um **Submódulo** do ecossistema principal `VETTRYX WP Core`. Ele não deve mais ser instalado como um plugin standalone (isolado) nos clientes.

Este submódulo atua como a central de privacidade e conformidade do ecossistema VETTRYX Tech, garantindo o cumprimento ágil das normativas da Lei Geral de Proteção de Dados (LGPD) e das exigências contratuais rigorosas de notificação de incidentes (SLA).

## 🚀 Funcionalidades

* **Direitos dos Titulares (Art. 18 LGPD):** Centraliza e facilita o acesso às ferramentas nativas do WordPress para exportação e exclusão definitiva de dados pessoais, permitindo respostas rápidas a solicitações de usuários e clientes.
* **Alerta de Incidente (SLA 24h):** Sistema de notificação de emergência integrado. Permite disparar comunicados oficiais ao Controlador de Dados (cliente) detalhando instabilidades, injeções de malware ou suspeitas de vazamentos, garantindo o cumprimento de cláusulas de notificação imediata.
* **Interface Unificada:** Agrega ferramentas de privacidade que ficam ocultas no painel nativo do WordPress para dentro do ecossistema e Design System da VETTRYX Tech, agregando percepção de valor e segurança ao serviço prestado.

## ⚙️ Arquitetura e Deploy (CI/CD)

Este repositório não gera mais arquivos `.zip` para instalação manual. O fluxo de deploy é 100% automatizado:

1. Qualquer push na branch `main` deste repositório dispara um webhook (Repository Dispatch) para o repositório principal do Core.
2. O repositório do Core puxa este código atualizado para dentro da pasta `/modules/`.
3. O GitHub Actions do Core empacota tudo e gera uma única Release oficial.

## 🛠️ Como Usar

Uma vez que o **VETTRYX WP Core** esteja instalado e o módulo ativado no painel do cliente:

1. Acesse **VETTRYX Tech > Compliance Manager** no painel do WordPress.
2. Utilize os botões **Exportar/Apagar Dados Pessoais** para gerenciar solicitações legais de usuários finais.
3. Em caso de crise de segurança operacional, preencha o formulário **Alerta de Incidente (SLA 24h)** com o e-mail do cliente, selecione o tipo de incidente e descreva o status técnico da contenção. O sistema enviará um comunicado formal e documentado imediatamente.

---

**VETTRYX Tech**
*Transformando ideias em experiências digitais.*

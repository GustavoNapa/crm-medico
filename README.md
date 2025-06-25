# CRM Médico - Sistema Completo

## Visão Geral

O CRM Médico é um sistema completo de gerenciamento de relacionamento com clientes desenvolvido especificamente para profissionais da área médica. O sistema integra funcionalidades avançadas de comunicação via WhatsApp, gestão de funis de vendas através de sistema Kanban, histórico detalhado de interações e sistema de webhooks para atualizações dinâmicas.

## Tecnologias Utilizadas

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Bootstrap 5, JavaScript ES6+
- **Banco de Dados**: SQLite (desenvolvimento) / MySQL (produção)
- **Integração WhatsApp**: Evolution API
- **Autenticação**: Laravel Breeze
- **Interface**: Responsive Design com Bootstrap

## Funcionalidades Principais

### 1. Sistema de Autenticação
- Login e registro de usuários
- Proteção de rotas com middleware
- Interface responsiva para desktop e mobile

### 2. Integração WhatsApp via Evolution API
- Envio e recebimento de mensagens
- Suporte a diferentes tipos de mídia
- Histórico completo de conversas
- Status de entrega e leitura

### 3. Sistema Kanban para Funis de Vendas
- Pipelines personalizáveis
- Drag-and-drop para movimentação de negócios
- Estatísticas em tempo real
- Valores e metas por pipeline
- Histórico de movimentações

### 4. Gestão de Contatos
- Cadastro completo de pacientes/clientes
- Histórico de interações
- Integração com WhatsApp
- Campos personalizáveis

### 5. Histórico Detalhado e Jornada do Cliente
- Timeline completa de interações
- Atividades manuais e automáticas
- Filtros por tipo e período
- Exportação de dados (JSON, CSV)
- Visualização de jornada do negócio

### 6. Sistema de Webhooks Dinâmicos
- Mapeamentos personalizáveis
- Transformações de dados
- Condições de aplicação
- Logs detalhados
- Reprocessamento automático

## Estrutura do Projeto

```
crm-medico/
├── app/
│   ├── Http/Controllers/
│   │   ├── KanbanController.php
│   │   ├── WhatsAppController.php
│   │   ├── HistoryController.php
│   │   └── WebhookController.php
│   ├── Models/
│   │   ├── Contact.php
│   │   ├── Deal.php
│   │   ├── Pipeline.php
│   │   ├── Activity.php
│   │   ├── WhatsAppMessage.php
│   │   ├── WebhookMapping.php
│   │   └── WebhookLog.php
│   └── Services/
│       └── EvolutionApiService.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/
│       └── crm/
└── routes/
    └── web.php
```

## Instalação e Configuração

### Pré-requisitos
- PHP 8.2 ou superior
- Composer
- Node.js e NPM
- Servidor web (Apache/Nginx)
- Banco de dados (MySQL/SQLite)

### Passos de Instalação

1. **Clone o repositório**
```bash
git clone [URL_DO_REPOSITORIO]
cd crm-medico
```

2. **Instale as dependências**
```bash
composer install
npm install
```

3. **Configure o ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure o banco de dados**
Edite o arquivo `.env` com as configurações do seu banco:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm_medico
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

5. **Execute as migrações**
```bash
php artisan migrate
php artisan db:seed --class=KanbanSeeder
```

6. **Compile os assets**
```bash
npm run build
```

7. **Configure a Evolution API**
Edite o arquivo `.env` com as configurações da Evolution API:
```env
EVOLUTION_API_URL=https://sua-evolution-api.com
EVOLUTION_API_KEY=sua_chave_api
EVOLUTION_INSTANCE_NAME=sua_instancia
```

8. **Inicie o servidor**
```bash
php artisan serve
```

## Configuração da Evolution API

### 1. Configuração Básica
- URL da API: Configure no arquivo `.env`
- Chave de autenticação: Obtenha na sua instância Evolution
- Nome da instância: Defina um nome único

### 2. Webhooks
Configure os webhooks na Evolution API para apontar para:
```
POST /webhook/evolution
```

### 3. Eventos Suportados
- Mensagens recebidas
- Status de entrega
- Conexão/desconexão
- Eventos de grupo

## Sistema Kanban

### Pipelines Padrão
1. **Prospecção**: Leads iniciais
2. **Qualificação**: Leads qualificados
3. **Proposta**: Propostas enviadas
4. **Negociação**: Em negociação
5. **Fechamento**: Pronto para fechar

### Funcionalidades
- Arrastar e soltar negócios entre pipelines
- Estatísticas em tempo real
- Filtros por período e responsável
- Histórico de movimentações
- Metas e valores por pipeline

## Sistema de Webhooks

### Mapeamentos
Configure mapeamentos para atualizar automaticamente:
- Campos de contatos
- Dados de negócios
- Atividades
- Mensagens WhatsApp

### Transformações Disponíveis
- Formatação de texto (maiúscula, minúscula)
- Formatação de telefone
- Formatação de data
- Substituição de texto
- Valores padrão
- Expressões regulares

### Condições
- Igualdade
- Diferença
- Contém texto
- Existe campo
- Operadores customizados

## API Endpoints

### Autenticação
```
POST /login
POST /register
POST /logout
```

### Kanban
```
GET /crm/kanban
GET /crm/kanban/data
POST /crm/kanban/deals
PUT /crm/kanban/deals/{id}
DELETE /crm/kanban/deals/{id}
POST /crm/kanban/move-deal
```

### WhatsApp
```
POST /whatsapp/send-message
POST /whatsapp/send-media
GET /whatsapp/messages
POST /whatsapp/check-whatsapp
```

### Histórico
```
GET /crm/history/contact/{id}
GET /crm/history/deal/{id}
POST /crm/history/activity
GET /crm/history/stats
```

### Webhooks
```
POST /webhook/evolution
POST /webhook/{source}
GET /crm/webhooks/mappings
POST /crm/webhooks/mappings
PUT /crm/webhooks/mappings/{id}
DELETE /crm/webhooks/mappings/{id}
```

## Modelos de Dados

### Contact (Contato)
- Nome, email, telefone, WhatsApp
- Data de nascimento, cidade, estado
- Origem, valor potencial
- Última interação

### Deal (Negócio)
- Título, descrição, valor
- Pipeline, posição
- Data prevista de fechamento
- Status (ativo, ganho, perdido)
- Responsável

### Activity (Atividade)
- Tipo, título, descrição
- Data da atividade, duração
- Contato e negócio relacionados
- Resultado

### WhatsAppMessage (Mensagem)
- Conteúdo, tipo de mensagem
- Direção (entrada/saída)
- Status de entrega
- Mídia anexada

## Segurança

### Autenticação
- Middleware de autenticação em todas as rotas protegidas
- Tokens CSRF em formulários
- Validação de entrada de dados

### Webhooks
- Logs detalhados de todas as requisições
- Validação de origem
- Rate limiting

### Dados
- Sanitização de entrada
- Validação de tipos de dados
- Proteção contra SQL injection

## Performance

### Otimizações
- Eager loading nos relacionamentos
- Índices no banco de dados
- Cache de consultas frequentes
- Paginação de resultados

### Monitoramento
- Logs de aplicação
- Métricas de webhook
- Tempo de resposta

## Manutenção

### Logs
- Logs de aplicação em `storage/logs/`
- Logs de webhook no banco de dados
- Rotação automática de logs

### Backup
- Backup automático do banco de dados
- Backup de arquivos de mídia
- Versionamento de código

### Atualizações
- Migrações de banco de dados
- Versionamento semântico
- Changelog detalhado

## Troubleshooting

### Problemas Comuns

#### Evolution API não conecta
1. Verifique a URL e chave da API
2. Confirme se a instância está ativa
3. Teste a conectividade de rede

#### Webhooks não funcionam
1. Verifique a URL do webhook
2. Confirme se o endpoint está acessível
3. Analise os logs de webhook

#### Kanban não carrega
1. Verifique se as migrações foram executadas
2. Confirme se há dados de exemplo
3. Analise o console do navegador

### Logs Importantes
```bash
# Logs da aplicação
tail -f storage/logs/laravel.log

# Logs do servidor web
tail -f /var/log/nginx/error.log

# Logs do banco de dados
# Verifique a configuração específica do seu SGBD
```

## Contribuição

### Padrões de Código
- PSR-12 para PHP
- ESLint para JavaScript
- Comentários em português
- Testes unitários obrigatórios

### Processo de Desenvolvimento
1. Fork do repositório
2. Criação de branch feature
3. Desenvolvimento e testes
4. Pull request com descrição detalhada
5. Code review
6. Merge após aprovação

## Licença

Este projeto está licenciado sob a licença MIT. Veja o arquivo LICENSE para mais detalhes.

## Suporte

Para suporte técnico ou dúvidas sobre o sistema:
- Documentação: [Link da documentação]
- Issues: [Link do GitHub Issues]
- Email: suporte@crmmedico.com

## Changelog

### Versão 1.0.0 (2025-06-25)
- Lançamento inicial
- Sistema de autenticação
- Integração WhatsApp via Evolution API
- Sistema Kanban para funis de vendas
- Histórico detalhado de interações
- Sistema de webhooks dinâmicos
- Interface responsiva
- Documentação completa

---

**Desenvolvido com ❤️ para profissionais da área médica**


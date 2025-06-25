# Menu do CRM Médico

## Visão Geral
O sistema de menu do CRM Médico foi desenvolvido para ser intuitivo, responsivo e moderno, proporcionando uma experiência de usuário excepcional.

## Estrutura do Menu

### Menu Principal (Sidebar)
O menu lateral está organizado em seções lógicas:

#### 🏠 Dashboard
- **Rota:** `/crm/dashboard`
- **Função:** Visão geral do sistema com métricas e estatísticas

#### 💼 Seção Vendas
- **Funil de Vendas** (`/crm/kanban`) - Sistema Kanban para gestão de negócios
- **Contatos** (`/crm/contacts`) - Gestão completa de pacientes e leads

#### 💬 Seção Comunicação
- **WhatsApp Chat** (`/crm/chat`) - Interface de conversas
- **Config WhatsApp** (`/whatsapp`) - Configurações da Evolution API

#### 📊 Seção Relatórios
- **Histórico** (`/crm/history/recent`) - Atividades e interações
- **Estatísticas** (`/crm/kanban/stats`) - Métricas de desempenho

#### 🔗 Seção Integrações
- **Webhooks** (`/crm/webhooks/view`) - Gerenciamento de integrações

#### ⚙️ Seção Sistema
- **Configurações** - Modal de configurações do sistema
- **Ajuda** (`/crm/documentation`) - Documentação completa
- **Sair** - Logout do sistema

### Navbar Superior
A barra superior contém:

#### 📍 Breadcrumb
- Navegação hierárquica das páginas
- Links clicáveis para retornar a seções anteriores

#### 🔔 Notificações
- Badge dinâmico com contador
- Dropdown com lista de notificações
- Atualização automática via JavaScript

#### 📱 Status WhatsApp
- Indicador visual do status da conexão
- Atualização em tempo real

#### 👤 Perfil do Usuário
- Avatar personalizado com iniciais
- Dropdown com opções de perfil
- Configurações rápidas

## Características Técnicas

### 🎨 Design
- **Gradiente Moderno:** Linear gradient azul/roxo
- **Responsivo:** Adaptável a diferentes tamanhos de tela
- **Animações:** Transições suaves e efeitos hover
- **Ícones:** Font Awesome 6.0 para consistência visual

### 📱 Responsividade
- **Desktop:** Sidebar fixa com navegação completa
- **Mobile:** Sidebar colapsável com toggle
- **Tablet:** Adaptação automática do layout

### ⚡ JavaScript Interativo
- **Classe CRMNavigation:** Gerencia toda a interatividade
- **Notificações:** Sistema de notificações em tempo real
- **Status Online:** Verificação automática de conectividade
- **Utilities:** Funções auxiliares para formatação

## Arquivos Relacionados

### 📁 Views
- `resources/views/layouts/crm.blade.php` - Layout principal
- `resources/views/layouts/app.blade.php` - Layout de autenticação
- `resources/views/crm/dashboard.blade.php` - Dashboard
- `resources/views/crm/contacts.blade.php` - Gestão de contatos
- `resources/views/crm/webhooks.blade.php` - Gerenciamento de webhooks
- `resources/views/crm/documentation.blade.php` - Documentação

### 🎨 Estilos
- `public/css/crm-style.css` - Estilos personalizados
- Variáveis CSS para cores e dimensões
- Classes utilitárias para componentes

### 🔧 JavaScript
- `public/js/crm-navigation.js` - Lógica de navegação
- Classe CRMNavigation para gerenciamento
- Utilitários globais (CRM.*)

### 🛣️ Rotas
- `routes/web.php` - Definição de todas as rotas
- Agrupamento por funcionalidade
- Middleware de autenticação

## Como Usar

### Navegação Básica
1. Use o menu lateral para acessar as principais funcionalidades
2. O breadcrumb mostra sua localização atual
3. Clique nas notificações para ver atualizações

### Customização
1. **Cores:** Modifique as variáveis CSS em `crm-style.css`
2. **Ícones:** Altere as classes do Font Awesome nas views
3. **Estrutura:** Edite o arquivo `layouts/crm.blade.php`

### Adicionando Novos Itens
1. **Rota:** Adicione em `routes/web.php`
2. **Link:** Inclua no sidebar em `layouts/crm.blade.php`
3. **Ícone:** Escolha um ícone apropriado do Font Awesome
4. **View:** Crie a view correspondente em `resources/views/crm/`

## Funcionalidades Especiais

### 🔔 Sistema de Notificações
```javascript
// Mostrar notificação
CRM.notify('Mensagem de sucesso', 'success');
CRM.notify('Mensagem de erro', 'error');
```

### 📱 Formatação de dados
```javascript
// Formatar telefone
CRM.formatPhone('11999999999'); // (11) 99999-9999

// Formatar moeda
CRM.formatCurrency(1500); // R$ 1.500,00
```

### ✅ Confirmações
```javascript
// Confirmar ação
CRM.confirm('Deseja excluir este item?', () => {
    // Ação confirmada
});
```

## Manutenção

### Atualizar Notificações
As notificações são carregadas automaticamente via JavaScript. Para modificar:
1. Edite o método `fetchNotifications()` em `crm-navigation.js`
2. Integre com sua API de notificações
3. Ajuste o formato dos dados conforme necessário

### Modificar Status WhatsApp
O status é verificado automaticamente. Para personalizar:
1. Modifique `checkWhatsAppStatus()` em `crm-navigation.js`
2. Integre com sua API de status
3. Ajuste os indicadores visuais

### Adicionar Seções
Para adicionar uma nova seção no menu:
1. Adicione um divisor: `<div class="sidebar-section mt-3">`
2. Inclua o título: `<small class="text-uppercase text-white-50 fw-bold px-3">Nova Seção</small>`
3. Adicione os links correspondentes

## Compatibilidade
- **Bootstrap 5.3+**
- **jQuery 3.6+**
- **Font Awesome 6.0+**
- **PHP 8.2+**
- **Laravel 11+**

## Suporte
Para dúvidas ou problemas relacionados ao menu:
1. Consulte a documentação interna (`/crm/documentation`)
2. Verifique os logs do navegador para erros JavaScript
3. Valide se todas as rotas estão definidas corretamente
4. Confirme se os assets estão sendo carregados

## Licença
Este menu foi desenvolvido especificamente para o CRM Médico e segue as diretrizes de design do sistema.

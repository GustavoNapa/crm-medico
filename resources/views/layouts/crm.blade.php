<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CRM Médico')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/crm-style.css') }}" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 0;
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .sidebar-section {
            margin: 15px 0 8px 0;
        }
        .sidebar-section small {
            font-size: 0.7rem;
            letter-spacing: 1px;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
        }
        .navbar-brand {
            font-weight: bold;
            color: #667eea !important;
        }
        .status-online {
            color: #28a745;
        }
        .status-offline {
            color: #dc3545;
        }
        .chat-container {
            height: 500px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            background: white;
        }
        .message-sent {
            background: #007bff;
            color: white;
            margin-left: auto;
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 18px 18px 5px 18px;
            margin-bottom: 10px;
        }
        .message-received {
            background: #e9ecef;
            color: #333;
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 18px 18px 18px 5px;
            margin-bottom: 10px;
        }
        .kanban-column {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin: 0 10px;
            min-height: 600px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .kanban-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .kanban-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .contact-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-heartbeat"></i> CRM Médico
                    </h4>
                    <nav class="nav flex-column">
                        <!-- Dashboard -->
                        <a class="nav-link {{ request()->routeIs('crm.dashboard') ? 'active' : '' }}" href="{{ route('crm.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        
                        <!-- Seção Vendas -->
                        <div class="sidebar-section mt-3">
                            <small class="text-uppercase text-white-50 fw-bold px-3">Vendas</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('crm.kanban*') ? 'active' : '' }}" href="{{ route('crm.kanban') }}">
                            <i class="fas fa-columns me-2"></i> Funil de Vendas
                        </a>
                        <a class="nav-link {{ request()->routeIs('crm.contacts*') ? 'active' : '' }}" href="{{ route('crm.contacts') }}">
                            <i class="fas fa-users me-2"></i> Contatos
                        </a>
                        
                        <!-- Seção Comunicação -->
                        <div class="sidebar-section mt-3">
                            <small class="text-uppercase text-white-50 fw-bold px-3">Comunicação</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('crm.chat') ? 'active' : '' }}" href="{{ route('crm.chat') }}">
                            <i class="fab fa-whatsapp me-2"></i> WhatsApp Chat
                        </a>
                        <a class="nav-link {{ request()->routeIs('whatsapp.*') ? 'active' : '' }}" href="{{ route('whatsapp.index') }}">
                            <i class="fas fa-mobile-alt me-2"></i> Config WhatsApp
                        </a>
                        
                        <!-- Seção Relatórios -->
                        <div class="sidebar-section mt-3">
                            <small class="text-uppercase text-white-50 fw-bold px-3">Relatórios</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('crm.history.*') ? 'active' : '' }}" href="{{ route('crm.history.recent') }}">
                            <i class="fas fa-history me-2"></i> Histórico
                        </a>
                        <a class="nav-link" href="{{ route('crm.kanban.stats') }}">
                            <i class="fas fa-chart-bar me-2"></i> Estatísticas
                        </a>
                        
                        <!-- Seção Integrações -->
                        <div class="sidebar-section mt-3">
                            <small class="text-uppercase text-white-50 fw-bold px-3">Integrações</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('crm.webhooks.*') ? 'active' : '' }}" href="{{ route('crm.webhooks.view') }}">
                            <i class="fas fa-plug me-2"></i> Webhooks
                        </a>
                        
                        <hr class="my-3">
                        
                        <!-- Seção Sistema -->
                        <div class="sidebar-section">
                            <small class="text-uppercase text-white-50 fw-bold px-3">Sistema</small>
                        </div>
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal">
                            <i class="fas fa-cog me-2"></i> Configurações
                        </a>
                        <a class="nav-link" href="{{ route('crm.documentation') }}">
                            <i class="fas fa-question-circle me-2"></i> Ajuda
                        </a>
                        <a class="nav-link" href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Sair
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content p-0">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1">
                            <i class="fas fa-{{ request()->routeIs('crm.dashboard') ? 'tachometer-alt' : (request()->routeIs('crm.kanban*') ? 'columns' : (request()->routeIs('crm.contacts*') ? 'users' : (request()->routeIs('crm.chat') ? 'comments' : (request()->routeIs('whatsapp.*') ? 'mobile-alt' : (request()->routeIs('crm.webhooks.*') ? 'plug' : 'home'))))) }} me-2"></i>
                            @yield('page-title', 'Dashboard')
                        </span>
                        
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb" class="d-none d-md-block">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('crm.dashboard') }}">Home</a></li>
                                @if(!request()->routeIs('crm.dashboard'))
                                    <li class="breadcrumb-item active">@yield('page-title', 'Página')</li>
                                @endif
                            </ol>
                        </nav>
                        
                        <div class="navbar-nav ms-auto d-flex flex-row align-items-center">
                            <!-- Notificações -->
                            <div class="nav-item dropdown me-3">
                                <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-bell"></i>
                                    <span class="notification-badge">3</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end notification-list" style="width: 350px;">
                                    <li><h6 class="dropdown-header d-flex justify-content-between">
                                        <span>Notificações</span>
                                        <small><a href="#" class="text-primary">Marcar todas como lidas</a></small>
                                    </h6></li>
                                    <!-- As notificações serão carregadas via JavaScript -->
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-center" href="#">Ver todas as notificações</a></li>
                                </ul>
                            </div>
                            
                            <!-- Status WhatsApp -->
                            <div class="nav-item me-3 whatsapp-status">
                                <span class="badge bg-success">
                                    <i class="fab fa-whatsapp me-1"></i> Online
                                </span>
                            </div>
                            
                            <!-- Perfil do Usuário -->
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    <div class="contact-avatar me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                    </div>
                                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><h6 class="dropdown-header">Minha Conta</h6></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                                        <i class="fas fa-user me-2"></i> Meu Perfil
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal">
                                        <i class="fas fa-cog me-2"></i> Configurações
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#helpModal">
                                        <i class="fas fa-question-circle me-2"></i> Ajuda & Suporte
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> Sair
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <!-- CRM Navigation JS -->
    <script src="{{ asset('js/crm-navigation.js') }}"></script>
    <!-- Custom JS -->
    <script>
        // CSRF Token para requisições AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Função para mostrar notificações
        function showNotification(message, type = 'success') {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            const alert = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="fas ${icon} me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            $('.main-content .p-4').prepend(alert);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                $('.alert').fadeOut();
            }, 5000);
        }

        // Função para formatar número de telefone
        function formatPhoneNumber(number) {
            return number.replace(/\D/g, '');
        }

        // Função para validar número de WhatsApp
        function isValidWhatsAppNumber(number) {
            const cleaned = formatPhoneNumber(number);
            return cleaned.length >= 10 && cleaned.length <= 15;
        }
    </script>
    
    <!-- Modais -->
    <!-- Modal Perfil -->
    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user me-2"></i>Meu Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div class="contact-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <h5>{{ Auth::user()->name }}</h5>
                        <p class="text-muted">{{ Auth::user()->email }}</p>
                    </div>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ Auth::user()->email }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="tel" class="form-control" placeholder="(11) 99999-9999">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Configurações -->
    <div class="modal fade" id="settingsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-cog me-2"></i>Configurações do Sistema</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="nav flex-column nav-pills" role="tablist">
                                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#general-settings">
                                    <i class="fas fa-sliders-h me-2"></i>Geral
                                </button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#whatsapp-settings">
                                    <i class="fab fa-whatsapp me-2"></i>WhatsApp
                                </button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#notification-settings">
                                    <i class="fas fa-bell me-2"></i>Notificações
                                </button>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="general-settings">
                                    <h6 class="fw-bold mb-3">Configurações Gerais</h6>
                                    <div class="mb-3">
                                        <label class="form-label">Fuso Horário</label>
                                        <select class="form-select">
                                            <option>America/Sao_Paulo</option>
                                            <option>America/New_York</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Idioma</label>
                                        <select class="form-select">
                                            <option>Português (BR)</option>
                                            <option>English (US)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="whatsapp-settings">
                                    <h6 class="fw-bold mb-3">Configurações WhatsApp</h6>
                                    <div class="mb-3">
                                        <label class="form-label">URL da Evolution API</label>
                                        <input type="url" class="form-control" placeholder="https://api.exemplo.com">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Token de Acesso</label>
                                        <input type="password" class="form-control">
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="notification-settings">
                                    <h6 class="fw-bold mb-3">Configurações de Notificação</h6>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" checked>
                                        <label class="form-check-label">Notificar novas mensagens</label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" checked>
                                        <label class="form-check-label">Notificar novos contatos</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Salvar Configurações</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajuda -->
    <div class="modal fade" id="helpModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-question-circle me-2"></i>Ajuda & Suporte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-book fa-3x text-primary mb-3"></i>
                                    <h5>Documentação</h5>
                                    <p class="text-muted">Consulte nossa documentação completa para aprender a usar todas as funcionalidades.</p>
                                    <a href="#" class="btn btn-outline-primary">Ver Documentação</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-headset fa-3x text-success mb-3"></i>
                                    <h5>Suporte Técnico</h5>
                                    <p class="text-muted">Entre em contato conosco para suporte técnico especializado.</p>
                                    <a href="mailto:suporte@crmmedico.com" class="btn btn-outline-success">Contatar Suporte</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="fw-bold">Perguntas Frequentes</h6>
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                            Como configurar o WhatsApp?
                                        </button>
                                    </h2>
                                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Acesse o menu "Config WhatsApp" e siga as instruções para conectar sua conta.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                            Como criar um novo funil de vendas?
                                        </button>
                                    </h2>
                                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            No menu "Funil de Vendas", clique em "Novo Pipeline" e configure as etapas conforme sua necessidade.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>


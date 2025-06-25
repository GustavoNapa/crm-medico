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
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
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
                        <a class="nav-link {{ request()->routeIs('crm.dashboard') ? 'active' : '' }}" href="{{ route('crm.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('crm.kanban') ? 'active' : '' }}" href="{{ route('crm.kanban') }}">
                            <i class="fas fa-columns me-2"></i> Funil de Vendas
                        </a>
                        <a class="nav-link {{ request()->routeIs('crm.contacts') ? 'active' : '' }}" href="{{ route('crm.contacts') }}">
                            <i class="fas fa-users me-2"></i> Contatos
                        </a>
                        <a class="nav-link {{ request()->routeIs('crm.chat') ? 'active' : '' }}" href="{{ route('crm.chat') }}">
                            <i class="fab fa-whatsapp me-2"></i> WhatsApp
                        </a>
                        <a class="nav-link {{ request()->routeIs('whatsapp.index') ? 'active' : '' }}" href="{{ route('whatsapp.index') }}">
                            <i class="fas fa-cog me-2"></i> Config WhatsApp
                        </a>
                        <hr class="my-3">
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
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <span class="navbar-brand">@yield('page-title', 'Dashboard')</span>
                        <div class="navbar-nav ms-auto">
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Perfil</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Configurações</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" 
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
    @stack('scripts')
</body>
</html>


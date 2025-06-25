@extends('layouts.crm')

@section('title', 'Dashboard - CRM Médico')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Cards de Estatísticas -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Contatos Totais
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">1,245</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Negócios Ativos
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">89</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-handshake fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Mensagens Hoje
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">156</div>
                    </div>
                    <div class="col-auto">
                        <i class="fab fa-whatsapp fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Taxa de Conversão
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">23.5%</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Gráfico de Vendas -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-area me-2"></i>Vendas nos Últimos 30 Dias
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart" height="320"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Funil de Vendas -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-funnel-dollar me-2"></i>Funil de Vendas
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Prospecção</span>
                        <span class="badge bg-secondary">45</span>
                    </div>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-secondary" style="width: 100%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Qualificação</span>
                        <span class="badge bg-info">32</span>
                    </div>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-info" style="width: 71%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Proposta</span>
                        <span class="badge bg-warning text-dark">18</span>
                    </div>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-warning" style="width: 40%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Fechamento</span>
                        <span class="badge bg-success">12</span>
                    </div>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" style="width: 27%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Atividades Recentes -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>Atividades Recentes
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Nova mensagem WhatsApp</h6>
                            <p class="mb-1 text-muted">Maria Silva enviou uma mensagem</p>
                            <small class="text-muted">Há 5 minutos</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Negócio movido para Proposta</h6>
                            <p class="mb-1 text-muted">João Santos - Consulta Cardiologia</p>
                            <small class="text-muted">Há 15 minutos</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Novo contato cadastrado</h6>
                            <p class="mb-1 text-muted">Ana Costa - (11) 99999-9999</p>
                            <small class="text-muted">Há 1 hora</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensagens Pendentes -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-envelope me-2"></i>Mensagens Pendentes
                </h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Carlos Oliveira</div>
                            <small class="text-muted">Gostaria de agendar uma consulta...</small>
                        </div>
                        <span class="badge bg-primary rounded-pill">2</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Fernanda Lima</div>
                            <small class="text-muted">Preciso de informações sobre...</small>
                        </div>
                        <span class="badge bg-primary rounded-pill">1</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Roberto Silva</div>
                            <small class="text-muted">Obrigado pelo atendimento!</small>
                        </div>
                        <span class="badge bg-secondary rounded-pill">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-left-primary {
        border-left: 4px solid #4e73df !important;
    }
    .border-left-success {
        border-left: 4px solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 4px solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 4px solid #f6c23e !important;
    }
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-marker {
        position: absolute;
        left: -23px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de vendas (exemplo básico)
const ctx = document.getElementById('myAreaChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['1', '5', '10', '15', '20', '25', '30'],
        datasets: [{
            label: 'Vendas',
            data: [12, 19, 8, 15, 22, 18, 25],
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush

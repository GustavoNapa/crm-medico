@extends('layouts.crm')

@section('title', 'Webhooks - CRM Médico')
@section('page-title', 'Gerenciamento de Webhooks')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Webhooks</h4>
        <p class="text-muted mb-0">Configure integrações automáticas com sistemas externos</p>
    </div>
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newMappingModal">
            <i class="fas fa-plus me-2"></i>Novo Mapeamento
        </button>
    </div>
</div>

<!-- Navegação por Abas -->
<ul class="nav nav-tabs mb-4" id="webhookTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="mappings-tab" data-bs-toggle="tab" data-bs-target="#mappings" type="button" role="tab">
            <i class="fas fa-cog me-2"></i>Mapeamentos
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs" type="button" role="tab">
            <i class="fas fa-list me-2"></i>Logs
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="test-tab" data-bs-toggle="tab" data-bs-target="#test" type="button" role="tab">
            <i class="fas fa-flask me-2"></i>Teste
        </button>
    </li>
</ul>

<div class="tab-content" id="webhookTabContent">
    <!-- Aba Mapeamentos -->
    <div class="tab-pane fade show active" id="mappings" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Mapeamentos Ativos</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Mapeamento 1 -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-left-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-success mb-1">Evolution API - Mensagens</h6>
                                        <p class="text-muted small mb-2">Processa mensagens recebidas do WhatsApp</p>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-success me-2">Ativo</span>
                                            <small class="text-muted">Última execução: há 5 min</small>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Editar</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-pause me-2"></i>Pausar</a></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>Excluir</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <small class="text-muted d-block">Sucesso</small>
                                        <strong class="text-success">98.5%</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Execuções</small>
                                        <strong>1,245</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Erros</small>
                                        <strong class="text-danger">18</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mapeamento 2 -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-left-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-info mb-1">CRM Integration</h6>
                                        <p class="text-muted small mb-2">Sincroniza contatos com sistema externo</p>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-info me-2">Ativo</span>
                                            <small class="text-muted">Última execução: há 1 hora</small>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Editar</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-pause me-2"></i>Pausar</a></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>Excluir</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <small class="text-muted d-block">Sucesso</small>
                                        <strong class="text-success">95.2%</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Execuções</small>
                                        <strong>856</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Erros</small>
                                        <strong class="text-danger">41</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mapeamento 3 -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-left-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-warning mb-1">Email Marketing</h6>
                                        <p class="text-muted small mb-2">Adiciona contatos à lista de email</p>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-warning text-dark me-2">Pausado</span>
                                            <small class="text-muted">Última execução: há 2 dias</small>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-play me-2"></i>Ativar</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Editar</a></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>Excluir</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <small class="text-muted d-block">Sucesso</small>
                                        <strong class="text-success">92.1%</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Execuções</small>
                                        <strong>432</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Erros</small>
                                        <strong class="text-danger">34</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aba Logs -->
    <div class="tab-pane fade" id="logs" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-list me-2"></i>Logs de Execução</h6>
                    <div>
                        <select class="form-select form-select-sm me-2" style="display: inline-block; width: auto;">
                            <option value="">Todos os status</option>
                            <option value="success">Sucesso</option>
                            <option value="error">Erro</option>
                            <option value="pending">Pendente</option>
                        </select>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-sync"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Data/Hora</th>
                                <th>Mapeamento</th>
                                <th>Status</th>
                                <th>Dados Processados</th>
                                <th>Tempo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <small>25/12/2025<br>14:30:15</small>
                                </td>
                                <td>Evolution API - Mensagens</td>
                                <td><span class="badge bg-success">Sucesso</span></td>
                                <td>
                                    <small>
                                        Mensagem processada<br>
                                        <strong>João Silva</strong>
                                    </small>
                                </td>
                                <td><small>120ms</small></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" title="Ver detalhes">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small>25/12/2025<br>14:25:08</small>
                                </td>
                                <td>CRM Integration</td>
                                <td><span class="badge bg-danger">Erro</span></td>
                                <td>
                                    <small>
                                        Falha na sincronização<br>
                                        <strong>API timeout</strong>
                                    </small>
                                </td>
                                <td><small>5000ms</small></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" title="Ver detalhes">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" title="Reprocessar">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small>25/12/2025<br>14:20:33</small>
                                </td>
                                <td>Evolution API - Mensagens</td>
                                <td><span class="badge bg-success">Sucesso</span></td>
                                <td>
                                    <small>
                                        Mensagem processada<br>
                                        <strong>Maria Santos</strong>
                                    </small>
                                </td>
                                <td><small>95ms</small></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" title="Ver detalhes">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <nav class="mt-3">
                    <ul class="pagination pagination-sm justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Anterior</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Próximo</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Aba Teste -->
    <div class="tab-pane fade" id="test" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-flask me-2"></i>Teste de Webhook</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">URL do Webhook</label>
                            <input type="url" class="form-control" value="{{ url('/webhook/evolution') }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mapeamento para Teste</label>
                            <select class="form-select">
                                <option>Evolution API - Mensagens</option>
                                <option>CRM Integration</option>
                                <option>Email Marketing</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dados JSON para Teste</label>
                            <textarea class="form-control" rows="10" id="testData">{
  "event": "message.received",
  "data": {
    "from": "5511999999999",
    "to": "5511888888888",
    "message": {
      "type": "text",
      "text": "Olá, gostaria de agendar uma consulta."
    },
    "timestamp": "2025-12-25T14:30:00Z"
  }
}</textarea>
                        </div>
                        <button class="btn btn-primary" id="testWebhook">
                            <i class="fas fa-play me-2"></i>Executar Teste
                        </button>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Resultado do Teste</label>
                            <div class="card bg-light" style="height: 300px;">
                                <div class="card-body">
                                    <div id="testResult" class="text-muted text-center">
                                        <i class="fas fa-flask fa-3x mb-3"></i>
                                        <p>Clique em "Executar Teste" para ver o resultado</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Headers de Resposta</label>
                            <div class="card bg-light" style="height: 150px;">
                                <div class="card-body">
                                    <div id="responseHeaders" class="text-muted small">
                                        Nenhum teste executado ainda
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo Mapeamento -->
<div class="modal fade" id="newMappingModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Novo Mapeamento de Webhook</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newMappingForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nome do Mapeamento *</label>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fonte *</label>
                                <select class="form-select" required>
                                    <option value="">Selecione...</option>
                                    <option value="evolution">Evolution API</option>
                                    <option value="external">Sistema Externo</option>
                                    <option value="custom">Personalizado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" rows="2" placeholder="Descreva o que este mapeamento faz..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Condições de Aplicação</label>
                        <div class="row">
                            <div class="col-md-4">
                                <select class="form-select">
                                    <option>event</option>
                                    <option>data.type</option>
                                    <option>data.from</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option>equals</option>
                                    <option>contains</option>
                                    <option>starts_with</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" placeholder="Valor">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transformações de Dados</label>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Campo Origem</th>
                                        <th>Campo Destino</th>
                                        <th>Transformação</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control form-control-sm" placeholder="data.from"></td>
                                        <td><input type="text" class="form-control form-control-sm" placeholder="contact.phone"></td>
                                        <td>
                                            <select class="form-select form-select-sm">
                                                <option>none</option>
                                                <option>format_phone</option>
                                                <option>uppercase</option>
                                                <option>lowercase</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>Adicionar Transformação
                        </button>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" checked>
                        <label class="form-check-label">Ativar mapeamento imediatamente</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Salvar Mapeamento</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-left-success {
        border-left: 4px solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 4px solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 4px solid #f6c23e !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Teste de webhook
    $('#testWebhook').click(function() {
        const button = $(this);
        const originalText = button.html();
        
        button.html('<i class="fas fa-spinner fa-spin me-2"></i>Executando...');
        button.prop('disabled', true);
        
        // Simular teste
        setTimeout(function() {
            $('#testResult').html(`
                <div class="text-success">
                    <i class="fas fa-check-circle fa-2x mb-3"></i>
                    <h6>Teste Executado com Sucesso!</h6>
                    <small>Status: 200 OK</small><br>
                    <small>Tempo de resposta: 145ms</small>
                </div>
            `);
            
            $('#responseHeaders').html(`
                <strong>Content-Type:</strong> application/json<br>
                <strong>X-Response-Time:</strong> 145ms<br>
                <strong>X-Webhook-ID:</strong> whk_123456789
            `);
            
            button.html(originalText);
            button.prop('disabled', false);
        }, 2000);
    });
});
</script>
@endpush

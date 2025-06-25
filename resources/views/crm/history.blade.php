@extends('layouts.crm')

@section('title', 'Histórico do Contato')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-history"></i> Histórico Detalhado
                    </h4>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="exportHistory('json')">
                            <i class="fas fa-download"></i> JSON
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="exportHistory('csv')">
                            <i class="fas fa-file-csv"></i> CSV
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="exportHistory('pdf')">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informações do Contato -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="contact-info">
                                <h5 id="contact-name">Carregando...</h5>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-envelope"></i> <span id="contact-email">-</span>
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-phone"></i> <span id="contact-phone">-</span>
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="fab fa-whatsapp"></i> <span id="contact-whatsapp">-</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-cards">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="stat-card text-center p-2 border rounded">
                                            <h6 class="mb-1" id="total-activities">0</h6>
                                            <small class="text-muted">Atividades</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-card text-center p-2 border rounded">
                                            <h6 class="mb-1" id="total-messages">0</h6>
                                            <small class="text-muted">Mensagens</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mt-2">
                                        <div class="stat-card text-center p-2 border rounded">
                                            <h6 class="mb-1" id="total-deals">0</h6>
                                            <small class="text-muted">Negócios</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mt-2">
                                        <div class="stat-card text-center p-2 border rounded">
                                            <h6 class="mb-1" id="total-value">R$ 0</h6>
                                            <small class="text-muted">Valor Total</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filter-type">Filtrar por tipo:</label>
                                <select class="form-control" id="filter-type" onchange="filterTimeline()">
                                    <option value="">Todos</option>
                                    <option value="activity">Atividades</option>
                                    <option value="whatsapp">WhatsApp</option>
                                    <option value="deal_created">Negócios Criados</option>
                                    <option value="deal_won">Negócios Ganhos</option>
                                    <option value="deal_lost">Negócios Perdidos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filter-period">Período:</label>
                                <select class="form-control" id="filter-period" onchange="filterTimeline()">
                                    <option value="">Todos</option>
                                    <option value="7">Últimos 7 dias</option>
                                    <option value="30">Últimos 30 dias</option>
                                    <option value="90">Últimos 90 dias</option>
                                    <option value="365">Último ano</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="timeline-container">
                        <div id="timeline" class="timeline">
                            <div class="text-center p-4">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Carregando...</span>
                                </div>
                                <p class="mt-2">Carregando histórico...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Nova Atividade -->
<div class="modal fade" id="newActivityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Atividade</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="newActivityForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="activity-type">Tipo de Atividade</label>
                        <select class="form-control" id="activity-type" name="type" required>
                            <option value="">Selecione...</option>
                            <option value="call">Ligação</option>
                            <option value="email">E-mail</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="meeting">Reunião</option>
                            <option value="note">Anotação</option>
                            <option value="task">Tarefa</option>
                            <option value="appointment">Consulta</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="activity-title">Título</label>
                        <input type="text" class="form-control" id="activity-title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="activity-description">Descrição</label>
                        <textarea class="form-control" id="activity-description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="activity-date">Data/Hora</label>
                                <input type="datetime-local" class="form-control" id="activity-date" name="activity_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="activity-duration">Duração (min)</label>
                                <input type="number" class="form-control" id="activity-duration" name="duration" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="activity-outcome">Resultado</label>
                        <textarea class="form-control" id="activity-outcome" name="outcome" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Atividade</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
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
    padding-left: 30px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 8px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-item.activity::before { background: #007bff; }
.timeline-item.whatsapp::before { background: #28a745; }
.timeline-item.deal_created::before { background: #17a2b8; }
.timeline-item.deal_won::before { background: #28a745; }
.timeline-item.deal_lost::before { background: #dc3545; }

.timeline-content {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 8px;
}

.timeline-title {
    font-weight: 600;
    margin: 0;
    color: #495057;
}

.timeline-date {
    font-size: 0.875rem;
    color: #6c757d;
}

.timeline-description {
    color: #6c757d;
    margin-bottom: 8px;
}

.timeline-user {
    font-size: 0.875rem;
    color: #007bff;
    font-weight: 500;
}

.stat-card {
    background: #f8f9fa;
}

.contact-info h5 {
    color: #495057;
    margin-bottom: 10px;
}

.btn-floating {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    z-index: 1000;
}
</style>

<script>
let contactId = {{ $contactId ?? 'null' }};
let timelineData = [];
let filteredData = [];

document.addEventListener('DOMContentLoaded', function() {
    if (contactId) {
        loadContactHistory();
    }
    
    // Configurar data padrão para nova atividade
    document.getElementById('activity-date').value = new Date().toISOString().slice(0, 16);
});

function loadContactHistory() {
    fetch(`/crm/history/contact/${contactId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayContactInfo(data.contact);
                displayStats(data.stats);
                timelineData = data.timeline;
                filteredData = timelineData;
                displayTimeline(filteredData);
            } else {
                showError('Erro ao carregar histórico');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showError('Erro ao carregar histórico');
        });
}

function displayContactInfo(contact) {
    document.getElementById('contact-name').textContent = contact.name;
    document.getElementById('contact-email').textContent = contact.email || '-';
    document.getElementById('contact-phone').textContent = contact.phone || '-';
    document.getElementById('contact-whatsapp').textContent = contact.whatsapp || '-';
}

function displayStats(stats) {
    document.getElementById('total-activities').textContent = stats.total_activities;
    document.getElementById('total-messages').textContent = stats.total_messages;
    document.getElementById('total-deals').textContent = stats.total_deals;
    document.getElementById('total-value').textContent = 'R$ ' + stats.total_value.toLocaleString('pt-BR', {minimumFractionDigits: 2});
}

function displayTimeline(data) {
    const timeline = document.getElementById('timeline');
    
    if (data.length === 0) {
        timeline.innerHTML = `
            <div class="text-center p-4">
                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                <p class="text-muted">Nenhum histórico encontrado</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    
    data.forEach(item => {
        const date = new Date(item.date);
        const formattedDate = date.toLocaleDateString('pt-BR') + ' ' + date.toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'});
        
        html += `
            <div class="timeline-item ${item.type}" data-type="${item.type}" data-date="${item.date}">
                <div class="timeline-content">
                    <div class="timeline-header">
                        <h6 class="timeline-title">
                            <i class="${item.icon}"></i> ${item.title}
                        </h6>
                        <span class="timeline-date">${formattedDate}</span>
                    </div>
                    ${item.description ? `<div class="timeline-description">${item.description}</div>` : ''}
                    <div class="timeline-user">
                        <i class="fas fa-user"></i> ${item.user}
                    </div>
                </div>
            </div>
        `;
    });
    
    timeline.innerHTML = html;
}

function filterTimeline() {
    const typeFilter = document.getElementById('filter-type').value;
    const periodFilter = document.getElementById('filter-period').value;
    
    let filtered = timelineData;
    
    // Filtrar por tipo
    if (typeFilter) {
        filtered = filtered.filter(item => item.type === typeFilter);
    }
    
    // Filtrar por período
    if (periodFilter) {
        const days = parseInt(periodFilter);
        const cutoffDate = new Date();
        cutoffDate.setDate(cutoffDate.getDate() - days);
        
        filtered = filtered.filter(item => new Date(item.date) >= cutoffDate);
    }
    
    filteredData = filtered;
    displayTimeline(filteredData);
}

function exportHistory(format) {
    const url = `/crm/history/contact/${contactId}/export?format=${format}`;
    window.open(url, '_blank');
}

function showNewActivityModal() {
    document.getElementById('newActivityForm').reset();
    document.getElementById('activity-date').value = new Date().toISOString().slice(0, 16);
    $('#newActivityModal').modal('show');
}

// Formulário de nova atividade
document.getElementById('newActivityForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('contact_id', contactId);
    
    fetch('/crm/history/activity', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#newActivityModal').modal('hide');
            loadContactHistory(); // Recarregar timeline
            showSuccess('Atividade criada com sucesso');
        } else {
            showError(data.message || 'Erro ao criar atividade');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showError('Erro ao criar atividade');
    });
});

function showSuccess(message) {
    // Implementar notificação de sucesso
    alert(message);
}

function showError(message) {
    // Implementar notificação de erro
    alert(message);
}
</script>

<!-- Botão flutuante para nova atividade -->
<button class="btn btn-primary btn-floating" onclick="showNewActivityModal()" title="Nova Atividade">
    <i class="fas fa-plus"></i>
</button>
@endsection


@extends('layouts.crm')

@section('title', 'Funil de Vendas - CRM Médico')
@section('page-title', 'Funil de Vendas')

@section('content')
<!-- Estatísticas do Funil -->
<div class="row mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">{{ number_format($totalValue, 2, ",", ".") }}</h3>
                <p class="text-muted mb-0">Valor Total em Negociação</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-info">{{ $totalDeals }}</h3>
                <p class="text-muted mb-0">Negócios Ativos</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">{{ $wonDeals }}</h3>
                <p class="text-muted mb-0">Ganhos este Mês</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-danger">{{ $lostDeals }}</h3>
                <p class="text-muted mb-0">Perdidos este Mês</p>
            </div>
        </div>
    </div>
</div>

<!-- Controles do Kanban -->
<div class="row mb-3">
    <div class="col-md-6">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newDealModal">
            <i class="fas fa-plus"></i> Novo Negócio
        </button>
        <button class="btn btn-outline-primary" id="refreshKanban">
            <i class="fas fa-sync-alt"></i> Atualizar
        </button>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group">
            <button class="btn btn-outline-secondary" id="viewStats">
                <i class="fas fa-chart-bar"></i> Estatísticas
            </button>
            <button class="btn btn-outline-secondary" id="exportData">
                <i class="fas fa-download"></i> Exportar
            </button>
        </div>
    </div>
</div>

<!-- Kanban Board -->
<div class="kanban-board">
    <div class="row" id="kanbanContainer">
        @foreach($pipelines as $pipeline)
        <div class="col-md-2 kanban-column-wrapper" data-pipeline-id="{{ $pipeline->id }}">
            <div class="kanban-column">
                <div class="kanban-header d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0" style="color: {{ $pipeline->color }}">
                        <i class="fas fa-circle me-1"></i>
                        {{ $pipeline->name }}
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Editar</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-plus me-2"></i>Novo Negócio</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="kanban-stats mb-3">
                    <small class="text-muted">
                        {{ $pipeline->deals_count }} negócios • R$ {{ number_format($pipeline->total_value, 0, ',', '.') }}
                    </small>
                </div>

                <div class="kanban-deals" data-pipeline-id="{{ $pipeline->id }}">
                    @foreach($pipeline->deals as $deal)
                    <div class="kanban-card" data-deal-id="{{ $deal->id }}" draggable="true">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-1">{{ $deal->title }}</h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link text-muted p-0" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item edit-deal" href="#" data-deal-id="{{ $deal->id }}">
                                        <i class="fas fa-edit me-2"></i>Editar
                                    </a></li>
                                    <li><a class="dropdown-item win-deal" href="#" data-deal-id="{{ $deal->id }}">
                                        <i class="fas fa-check me-2 text-success"></i>Marcar como Ganho
                                    </a></li>
                                    <li><a class="dropdown-item lose-deal" href="#" data-deal-id="{{ $deal->id }}">
                                        <i class="fas fa-times me-2 text-danger"></i>Marcar como Perdido
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item delete-deal text-danger" href="#" data-deal-id="{{ $deal->id }}">
                                        <i class="fas fa-trash me-2"></i>Excluir
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-2">
                            <div class="contact-avatar me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                {{ $deal->contact->initials }}
                            </div>
                            <div class="flex-grow-1">
                                <small class="fw-bold">{{ $deal->contact->name }}</small>
                                @if($deal->contact->hasWhatsApp())
                                <br><small class="text-muted">
                                    <i class="fab fa-whatsapp text-success"></i> {{ $deal->contact->formatted_whatsapp }}
                                </small>
                                @endif
                            </div>
                        </div>
                        
                        @if($deal->value)
                        <div class="mb-2">
                            <span class="badge bg-success">{{ $deal->formatted_value }}</span>
                        </div>
                        @endif
                        
                        @if($deal->expected_close_date)
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $deal->expected_close_date->format('d/m/Y') }}
                                @if($deal->is_overdue)
                                <span class="text-danger">(Atrasado)</span>
                                @endif
                            </small>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>{{ $deal->user->name }}
                            </small>
                            <small class="text-muted">{{ $deal->last_activity_formatted }}</small>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Área de drop -->
                    <div class="kanban-drop-zone" data-pipeline-id="{{ $pipeline->id }}">
                        <div class="text-center text-muted p-3">
                            <i class="fas fa-plus-circle"></i>
                            <br>Arraste um negócio aqui
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal Novo Negócio -->
<div class="modal fade" id="newDealModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Negócio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newDealForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dealTitle" class="form-label">Título do Negócio *</label>
                                <input type="text" class="form-control" id="dealTitle" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dealPipeline" class="form-label">Pipeline *</label>
                                <select class="form-select" id="dealPipeline" required>
                                    <option value="">Selecione o pipeline</option>
                                    @foreach($pipelines as $pipeline)
                                    <option value="{{ $pipeline->id }}">{{ $pipeline->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dealContact" class="form-label">Contato *</label>
                                <select class="form-select" id="dealContact" required>
                                    <option value="">Selecione o contato</option>
                                    <!-- Contatos serão carregados via AJAX -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dealValue" class="form-label">Valor</label>
                                <input type="number" class="form-control" id="dealValue" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="dealExpectedDate" class="form-label">Data Esperada de Fechamento</label>
                        <input type="date" class="form-control" id="dealExpectedDate">
                    </div>
                    
                    <div class="mb-3">
                        <label for="dealDescription" class="form-label">Descrição</label>
                        <textarea class="form-control" id="dealDescription" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveDealBtn">
                    <i class="fas fa-save"></i> Salvar Negócio
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Negócio -->
<div class="modal fade" id="editDealModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Negócio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editDealForm">
                    <input type="hidden" id="editDealId">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editDealTitle" class="form-label">Título do Negócio *</label>
                                <input type="text" class="form-control" id="editDealTitle" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editDealValue" class="form-label">Valor</label>
                                <input type="number" class="form-control" id="editDealValue" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editDealExpectedDate" class="form-label">Data Esperada de Fechamento</label>
                        <input type="date" class="form-control" id="editDealExpectedDate">
                    </div>
                    
                    <div class="mb-3">
                        <label for="editDealDescription" class="form-label">Descrição</label>
                        <textarea class="form-control" id="editDealDescription" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="updateDealBtn">
                    <i class="fas fa-save"></i> Atualizar Negócio
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.kanban-board {
    overflow-x: auto;
    padding-bottom: 20px;
}

.kanban-column-wrapper {
    min-width: 300px;
    flex: 0 0 auto;
}

.kanban-column {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    margin: 0 5px;
    min-height: 600px;
    border: 2px dashed transparent;
    transition: all 0.3s;
}

.kanban-column.drag-over {
    border-color: #007bff;
    background: #e3f2fd;
}

.kanban-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    cursor: grab;
    transition: all 0.3s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.kanban-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.kanban-card.dragging {
    opacity: 0.5;
    transform: rotate(5deg);
}

.kanban-drop-zone {
    min-height: 100px;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    margin-top: 10px;
    display: none;
}

.kanban-drop-zone.show {
    display: block;
}

.kanban-header {
    border-bottom: 2px solid #dee2e6;
    padding-bottom: 10px;
}

.kanban-stats {
    background: rgba(255,255,255,0.7);
    padding: 8px;
    border-radius: 5px;
    text-align: center;
}

@media (max-width: 768px) {
    .kanban-board {
        flex-direction: column;
    }
    
    .kanban-column-wrapper {
        min-width: 100%;
        margin-bottom: 20px;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    let draggedElement = null;
    let kanbanData = {};

    // Inicializar Kanban
    initializeKanban();

    // Event Listeners
    $('#saveDealBtn').on('click', saveDeal);
    $('#updateDealBtn').on('click', updateDeal);
    $('#refreshKanban').on('click', loadKanbanData);
    
    $(document).on('click', '.edit-deal', editDeal);
    $(document).on('click', '.win-deal', winDeal);
    $(document).on('click', '.lose-deal', loseDeal);
    $(document).on('click', '.delete-deal', deleteDeal);

    function initializeKanban() {
        loadContacts();
        loadKanbanData();
        setupDragAndDrop();
    }

    function loadContacts() {
        // Carregar contatos para o select
        // Por enquanto, vamos usar dados estáticos
        const contacts = [
            {id: 1, name: 'João da Silva'},
            {id: 2, name: 'Maria Santos'},
            {id: 3, name: 'Pedro Costa'}
        ];
        
        const contactSelect = $('#dealContact');
        contactSelect.empty().append('<option value="">Selecione o contato</option>');
        
        contacts.forEach(contact => {
            contactSelect.append(`<option value="${contact.id}">${contact.name}</option>`);
        });
    }

    function loadKanbanData() {
        $.get('/crm/kanban/data')
            .done(function(response) {
                if (response.success) {
                    kanbanData = response;
                    updateKanbanDisplay();
                }
            })
            .fail(function() {
                showNotification('Erro ao carregar dados do Kanban', 'error');
            });
    }

    function updateKanbanDisplay() {
        // Atualizar contadores e estatísticas
        // Esta função seria implementada para atualizar a interface
        console.log('Dados do Kanban atualizados:', kanbanData);
    }

    function setupDragAndDrop() {
        $('.kanban-deals').each(function() {
            const pipelineId = $(this).data('pipeline-id');
            
            new Sortable(this, {
                group: 'kanban',
                animation: 150,
                ghostClass: 'kanban-card-ghost',
                chosenClass: 'kanban-card-chosen',
                dragClass: 'kanban-card-drag',
                
                onStart: function(evt) {
                    $('.kanban-drop-zone').addClass('show');
                },
                
                onEnd: function(evt) {
                    $('.kanban-drop-zone').removeClass('show');
                    
                    if (evt.from !== evt.to) {
                        const dealId = $(evt.item).data('deal-id');
                        const newPipelineId = $(evt.to).data('pipeline-id');
                        const newPosition = evt.newIndex;
                        
                        moveDeal(dealId, newPipelineId, newPosition);
                    }
                }
            });
        });
    }

    function saveDeal() {
        const formData = {
            title: $('#dealTitle').val(),
            pipeline_id: $('#dealPipeline').val(),
            contact_id: $('#dealContact').val(),
            value: $('#dealValue').val(),
            expected_close_date: $('#dealExpectedDate').val(),
            description: $('#dealDescription').val()
        };

        if (!formData.title || !formData.pipeline_id || !formData.contact_id) {
            showNotification('Preencha todos os campos obrigatórios', 'error');
            return;
        }

        $.post('/crm/kanban/deals', formData)
            .done(function(response) {
                if (response.success) {
                    showNotification('Negócio criado com sucesso!', 'success');
                    $('#newDealModal').modal('hide');
                    $('#newDealForm')[0].reset();
                    loadKanbanData();
                } else {
                    showNotification(response.message, 'error');
                }
            })
            .fail(function() {
                showNotification('Erro ao criar negócio', 'error');
            });
    }

    function editDeal(e) {
        e.preventDefault();
        const dealId = $(this).data('deal-id');
        
        // Carregar dados do deal
        // Por enquanto, vamos usar dados estáticos
        $('#editDealId').val(dealId);
        $('#editDealTitle').val('Consulta de Rotina');
        $('#editDealValue').val('150.00');
        $('#editDealExpectedDate').val('2025-01-15');
        $('#editDealDescription').val('Consulta de rotina para paciente');
        
        $('#editDealModal').modal('show');
    }

    function updateDeal() {
        const dealId = $('#editDealId').val();
        const formData = {
            title: $('#editDealTitle').val(),
            value: $('#editDealValue').val(),
            expected_close_date: $('#editDealExpectedDate').val(),
            description: $('#editDealDescription').val()
        };

        $.ajax({
            url: `/crm/kanban/deals/${dealId}`,
            method: 'PUT',
            data: formData
        })
        .done(function(response) {
            if (response.success) {
                showNotification('Negócio atualizado com sucesso!', 'success');
                $('#editDealModal').modal('hide');
                loadKanbanData();
            } else {
                showNotification(response.message, 'error');
            }
        })
        .fail(function() {
            showNotification('Erro ao atualizar negócio', 'error');
        });
    }

    function moveDeal(dealId, pipelineId, position) {
        $.post('/crm/kanban/move-deal', {
            deal_id: dealId,
            pipeline_id: pipelineId,
            position: position
        })
        .done(function(response) {
            if (response.success) {
                showNotification('Negócio movido com sucesso!', 'success');
                loadKanbanData();
            } else {
                showNotification(response.message, 'error');
            }
        })
        .fail(function() {
            showNotification('Erro ao mover negócio', 'error');
            loadKanbanData(); // Recarregar para reverter a mudança visual
        });
    }

    function winDeal(e) {
        e.preventDefault();
        const dealId = $(this).data('deal-id');
        
        if (confirm('Marcar este negócio como ganho?')) {
            $.post(`/crm/kanban/deals/${dealId}/win`)
                .done(function(response) {
                    if (response.success) {
                        showNotification('Negócio marcado como ganho!', 'success');
                        loadKanbanData();
                    } else {
                        showNotification(response.message, 'error');
                    }
                })
                .fail(function() {
                    showNotification('Erro ao marcar negócio como ganho', 'error');
                });
        }
    }

    function loseDeal(e) {
        e.preventDefault();
        const dealId = $(this).data('deal-id');
        
        const reason = prompt('Motivo da perda (opcional):');
        if (reason !== null) {
            $.post(`/crm/kanban/deals/${dealId}/lose`, { reason: reason })
                .done(function(response) {
                    if (response.success) {
                        showNotification('Negócio marcado como perdido', 'success');
                        loadKanbanData();
                    } else {
                        showNotification(response.message, 'error');
                    }
                })
                .fail(function() {
                    showNotification('Erro ao marcar negócio como perdido', 'error');
                });
        }
    }

    function deleteDeal(e) {
        e.preventDefault();
        const dealId = $(this).data('deal-id');
        
        if (confirm('Tem certeza que deseja excluir este negócio?')) {
            $.ajax({
                url: `/crm/kanban/deals/${dealId}`,
                method: 'DELETE'
            })
            .done(function(response) {
                if (response.success) {
                    showNotification('Negócio excluído com sucesso', 'success');
                    loadKanbanData();
                } else {
                    showNotification(response.message, 'error');
                }
            })
            .fail(function() {
                showNotification('Erro ao excluir negócio', 'error');
            });
        }
    }

    // Auto-refresh a cada 30 segundos
    setInterval(loadKanbanData, 30000);
});
</script>
@endpush


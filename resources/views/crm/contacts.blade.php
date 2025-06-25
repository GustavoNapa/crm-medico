@extends('layouts.crm')

@section('title', 'Contatos - CRM Médico')
@section('page-title', 'Gestão de Contatos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Contatos</h4>
        <p class="text-muted mb-0">Gerencie seus pacientes e leads</p>
    </div>
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newContactModal">
            <i class="fas fa-plus me-2"></i>Novo Contato
        </button>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Buscar contatos..." id="searchInput">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter">
                    <option value="">Todos os status</option>
                    <option value="lead">Lead</option>
                    <option value="prospect">Prospect</option>
                    <option value="client">Cliente</option>
                    <option value="inactive">Inativo</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="sourceFilter">
                    <option value="">Todas as origens</option>
                    <option value="whatsapp">WhatsApp</option>
                    <option value="website">Website</option>
                    <option value="referral">Indicação</option>
                    <option value="social">Redes Sociais</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" id="clearFilters">
                    <i class="fas fa-times me-1"></i>Limpar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Contatos -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="fas fa-users me-2"></i>Lista de Contatos</h6>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm active" id="gridView">
                    <i class="fas fa-th"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="listView">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Visualização em Grid -->
        <div id="contactsGrid" class="row">
            <!-- Contato 1 -->
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card contact-card h-100">
                    <div class="card-body text-center">
                        <div class="contact-avatar mx-auto mb-3">MS</div>
                        <h6 class="card-title mb-1">Maria Silva</h6>
                        <p class="text-muted small mb-2">maria@email.com</p>
                        <span class="badge bg-success mb-3">Cliente</span>
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-outline-success" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" title="Histórico">
                                <i class="fas fa-history"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contato 2 -->
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card contact-card h-100">
                    <div class="card-body text-center">
                        <div class="contact-avatar mx-auto mb-3">JS</div>
                        <h6 class="card-title mb-1">João Santos</h6>
                        <p class="text-muted small mb-2">joao@email.com</p>
                        <span class="badge bg-warning text-dark mb-3">Prospect</span>
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-outline-success" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" title="Histórico">
                                <i class="fas fa-history"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contato 3 -->
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card contact-card h-100">
                    <div class="card-body text-center">
                        <div class="contact-avatar mx-auto mb-3">AC</div>
                        <h6 class="card-title mb-1">Ana Costa</h6>
                        <p class="text-muted small mb-2">ana@email.com</p>
                        <span class="badge bg-info mb-3">Lead</span>
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-outline-success" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" title="Histórico">
                                <i class="fas fa-history"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contato 4 -->
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card contact-card h-100">
                    <div class="card-body text-center">
                        <div class="contact-avatar mx-auto mb-3">CO</div>
                        <h6 class="card-title mb-1">Carlos Oliveira</h6>
                        <p class="text-muted small mb-2">carlos@email.com</p>
                        <span class="badge bg-secondary mb-3">Inativo</span>
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-outline-success" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" title="Histórico">
                                <i class="fas fa-history"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visualização em Lista -->
        <div id="contactsList" class="d-none">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Status</th>
                            <th>Origem</th>
                            <th>Última Interação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="contact-avatar me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">MS</div>
                                    Maria Silva
                                </div>
                            </td>
                            <td>maria@email.com</td>
                            <td>(11) 99999-1111</td>
                            <td><span class="badge bg-success">Cliente</span></td>
                            <td><i class="fab fa-whatsapp text-success me-1"></i>WhatsApp</td>
                            <td>Há 2 horas</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" title="Histórico">
                                        <i class="fas fa-history"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="contact-avatar me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">JS</div>
                                    João Santos
                                </div>
                            </td>
                            <td>joao@email.com</td>
                            <td>(11) 99999-2222</td>
                            <td><span class="badge bg-warning text-dark">Prospect</span></td>
                            <td><i class="fas fa-globe text-primary me-1"></i>Website</td>
                            <td>Há 1 dia</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" title="Histórico">
                                        <i class="fas fa-history"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginação -->
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Anterior</a>
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

<!-- Modal Novo Contato -->
<div class="modal fade" id="newContactModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Novo Contato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newContactForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Telefone</label>
                                <input type="tel" class="form-control" placeholder="(11) 99999-9999">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">WhatsApp</label>
                                <input type="tel" class="form-control" placeholder="(11) 99999-9999">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option value="lead">Lead</option>
                                    <option value="prospect">Prospect</option>
                                    <option value="client">Cliente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Origem</label>
                                <select class="form-select">
                                    <option value="whatsapp">WhatsApp</option>
                                    <option value="website">Website</option>
                                    <option value="referral">Indicação</option>
                                    <option value="social">Redes Sociais</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cidade</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select class="form-select">
                                    <option value="">Selecione...</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="MG">Minas Gerais</option>
                                    <!-- Adicionar outros estados -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observações</label>
                        <textarea class="form-control" rows="3" placeholder="Informações adicionais sobre o contato..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Salvar Contato</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .contact-card {
        transition: all 0.3s;
        border: 1px solid #e3e6f0;
    }
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Alternar entre visualizações
    $('#gridView').click(function() {
        $(this).addClass('active');
        $('#listView').removeClass('active');
        $('#contactsGrid').removeClass('d-none');
        $('#contactsList').addClass('d-none');
    });

    $('#listView').click(function() {
        $(this).addClass('active');
        $('#gridView').removeClass('active');
        $('#contactsList').removeClass('d-none');
        $('#contactsGrid').addClass('d-none');
    });

    // Limpar filtros
    $('#clearFilters').click(function() {
        $('#searchInput').val('');
        $('#statusFilter').val('');
        $('#sourceFilter').val('');
    });

    // Máscaras para telefone
    $('input[type="tel"]').mask('(00) 00000-0000');
});
</script>
@endpush

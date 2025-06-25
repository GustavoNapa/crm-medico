@extends('layouts.crm')

@section('title', 'Chat WhatsApp - CRM Médico')
@section('page-title', 'Chat WhatsApp')

@section('content')
<div class="row">
    <!-- Lista de Contatos -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fab fa-whatsapp me-2"></i>Conversas</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newChatModal">
                    <i class="fas fa-plus"></i> Nova Conversa
                </button>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" id="contactsList">
                    <!-- Contatos serão carregados aqui via JavaScript -->
                    <div class="list-group-item d-flex align-items-center p-3">
                        <div class="contact-avatar me-3">JD</div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">João da Silva</h6>
                            <p class="mb-1 text-muted small">Última mensagem...</p>
                            <small class="text-muted">10:30</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary rounded-pill">2</span>
                        </div>
                    </div>
                    
                    <div class="list-group-item d-flex align-items-center p-3">
                        <div class="contact-avatar me-3">MS</div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Maria Santos</h6>
                            <p class="mb-1 text-muted small">Obrigada pelo atendimento!</p>
                            <small class="text-muted">09:15</small>
                        </div>
                    </div>
                    
                    <div class="list-group-item d-flex align-items-center p-3">
                        <div class="contact-avatar me-3">PC</div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Pedro Costa</h6>
                            <p class="mb-1 text-muted small">Quando é a próxima consulta?</p>
                            <small class="text-muted">Ontem</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary rounded-pill">1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Área de Chat -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="contact-avatar me-3">JD</div>
                    <div>
                        <h6 class="mb-0">João da Silva</h6>
                        <small class="text-muted">
                            <i class="fas fa-circle status-online"></i> Online
                        </small>
                    </div>
                </div>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary" title="Informações do contato">
                        <i class="fas fa-info-circle"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-primary" title="Histórico">
                        <i class="fas fa-history"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-primary" title="Anexar arquivo">
                        <i class="fas fa-paperclip"></i>
                    </button>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="chat-container" id="chatContainer">
                    <!-- Mensagens serão carregadas aqui -->
                    <div class="message-received">
                        <div>Olá, gostaria de agendar uma consulta</div>
                        <small class="text-muted">10:25</small>
                    </div>
                    
                    <div class="message-sent">
                        <div>Olá! Claro, vou verificar a agenda do doutor. Qual seria sua preferência de horário?</div>
                        <small class="text-muted">10:26</small>
                    </div>
                    
                    <div class="message-received">
                        <div>Prefiro pela manhã, se possível</div>
                        <small class="text-muted">10:27</small>
                    </div>
                    
                    <div class="message-sent">
                        <div>Perfeito! Temos disponibilidade na próxima terça-feira às 9h. Posso agendar para você?</div>
                        <small class="text-muted">10:28</small>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <form id="messageForm" class="d-flex">
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" type="button" title="Emoji">
                            <i class="fas fa-smile"></i>
                        </button>
                        <input type="text" class="form-control" id="messageInput" placeholder="Digite sua mensagem..." autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" title="Anexar">
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nova Conversa -->
<div class="modal fade" id="newChatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Conversa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newChatForm">
                    <div class="mb-3">
                        <label for="contactPhone" class="form-label">Número do WhatsApp</label>
                        <input type="tel" class="form-control" id="contactPhone" placeholder="(11) 99999-9999" required>
                        <div class="form-text">Digite o número com DDD</div>
                    </div>
                    <div class="mb-3">
                        <label for="contactName" class="form-label">Nome do Contato</label>
                        <input type="text" class="form-control" id="contactName" placeholder="Nome do paciente" required>
                    </div>
                    <div class="mb-3">
                        <label for="initialMessage" class="form-label">Mensagem Inicial (opcional)</label>
                        <textarea class="form-control" id="initialMessage" rows="3" placeholder="Digite uma mensagem inicial..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="startChatBtn">
                    <i class="fas fa-comments"></i> Iniciar Conversa
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Informações do Contato -->
<div class="modal fade" id="contactInfoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informações do Contato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Dados Pessoais</h6>
                        <p><strong>Nome:</strong> João da Silva</p>
                        <p><strong>Telefone:</strong> (11) 99999-9999</p>
                        <p><strong>Email:</strong> joao@email.com</p>
                        <p><strong>Data de Nascimento:</strong> 15/03/1985</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Histórico Médico</h6>
                        <p><strong>Última Consulta:</strong> 10/12/2024</p>
                        <p><strong>Próxima Consulta:</strong> 15/01/2025</p>
                        <p><strong>Médico Responsável:</strong> Dr. Silva</p>
                        <p><strong>Observações:</strong> Paciente hipertenso</p>
                    </div>
                </div>
                <hr>
                <h6>Funil de Vendas</h6>
                <div class="d-flex align-items-center">
                    <span class="badge bg-warning me-2">Prospecção</span>
                    <small class="text-muted">Movido em 10/12/2024</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Editar Informações</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentContact = null;
    let chatInterval = null;

    // Inicializar chat
    initializeChat();

    // Enviar mensagem
    $('#messageForm').on('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });

    // Iniciar nova conversa
    $('#startChatBtn').on('click', function() {
        startNewChat();
    });

    // Selecionar contato
    $(document).on('click', '.list-group-item', function() {
        selectContact($(this));
    });

    function initializeChat() {
        // Verificar status da conexão WhatsApp
        checkWhatsAppStatus();
        
        // Carregar contatos
        loadContacts();
        
        // Iniciar polling para novas mensagens
        startMessagePolling();
    }

    function checkWhatsAppStatus() {
        $.get('/whatsapp/status')
            .done(function(response) {
                if (response.success && response.data.state === 'open') {
                    showNotification('WhatsApp conectado com sucesso!', 'success');
                } else {
                    showNotification('WhatsApp não está conectado. Verifique a configuração.', 'error');
                }
            })
            .fail(function() {
                showNotification('Erro ao verificar status do WhatsApp', 'error');
            });
    }

    function loadContacts() {
        // Aqui você carregaria os contatos do banco de dados
        // Por enquanto, vamos usar dados estáticos
        console.log('Carregando contatos...');
    }

    function selectContact(contactElement) {
        // Remover seleção anterior
        $('.list-group-item').removeClass('active');
        
        // Selecionar novo contato
        contactElement.addClass('active');
        
        // Carregar mensagens do contato
        const contactName = contactElement.find('h6').text();
        const contactPhone = contactElement.data('phone') || '5511999999999';
        
        currentContact = {
            name: contactName,
            phone: contactPhone
        };
        
        loadMessages(contactPhone);
        updateChatHeader(contactName);
    }

    function loadMessages(contactPhone) {
        // Carregar mensagens do contato
        $('#chatContainer').html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Carregando mensagens...</div>');
        
        // Simular carregamento de mensagens
        setTimeout(() => {
            $('#chatContainer').html(`
                <div class="message-received">
                    <div>Olá, gostaria de agendar uma consulta</div>
                    <small class="text-muted">10:25</small>
                </div>
                <div class="message-sent">
                    <div>Olá! Claro, vou verificar a agenda do doutor. Qual seria sua preferência de horário?</div>
                    <small class="text-muted">10:26</small>
                </div>
            `);
            scrollToBottom();
        }, 1000);
    }

    function updateChatHeader(contactName) {
        $('.card-header h6').text(contactName);
    }

    function sendMessage() {
        const message = $('#messageInput').val().trim();
        
        if (!message) {
            return;
        }
        
        if (!currentContact) {
            showNotification('Selecione um contato primeiro', 'error');
            return;
        }

        // Adicionar mensagem na interface
        addMessageToChat(message, 'sent');
        
        // Limpar input
        $('#messageInput').val('');
        
        // Enviar via API
        $.post('/whatsapp/send-message', {
            number: currentContact.phone,
            message: message
        })
        .done(function(response) {
            if (response.success) {
                console.log('Mensagem enviada com sucesso');
            } else {
                showNotification('Erro ao enviar mensagem', 'error');
            }
        })
        .fail(function() {
            showNotification('Erro ao enviar mensagem', 'error');
        });
    }

    function addMessageToChat(message, type) {
        const messageClass = type === 'sent' ? 'message-sent' : 'message-received';
        const time = new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
        
        const messageHtml = `
            <div class="${messageClass}">
                <div>${message}</div>
                <small class="text-muted">${time}</small>
            </div>
        `;
        
        $('#chatContainer').append(messageHtml);
        scrollToBottom();
    }

    function scrollToBottom() {
        const chatContainer = document.getElementById('chatContainer');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function startNewChat() {
        const phone = $('#contactPhone').val().trim();
        const name = $('#contactName').val().trim();
        const initialMessage = $('#initialMessage').val().trim();
        
        if (!phone || !name) {
            showNotification('Preencha todos os campos obrigatórios', 'error');
            return;
        }
        
        if (!isValidWhatsAppNumber(phone)) {
            showNotification('Número de telefone inválido', 'error');
            return;
        }
        
        // Verificar se o número tem WhatsApp
        $.post('/whatsapp/check-whatsapp', {
            number: formatPhoneNumber(phone)
        })
        .done(function(response) {
            if (response.success) {
                // Adicionar contato à lista
                addContactToList(name, phone);
                
                // Enviar mensagem inicial se fornecida
                if (initialMessage) {
                    currentContact = { name: name, phone: formatPhoneNumber(phone) };
                    setTimeout(() => {
                        $('#messageInput').val(initialMessage);
                        sendMessage();
                    }, 500);
                }
                
                // Fechar modal
                $('#newChatModal').modal('hide');
                
                // Limpar formulário
                $('#newChatForm')[0].reset();
                
                showNotification('Conversa iniciada com sucesso!', 'success');
            } else {
                showNotification('Este número não possui WhatsApp', 'error');
            }
        })
        .fail(function() {
            showNotification('Erro ao verificar número', 'error');
        });
    }

    function addContactToList(name, phone) {
        const initials = name.split(' ').map(n => n[0]).join('').toUpperCase();
        const contactHtml = `
            <div class="list-group-item d-flex align-items-center p-3" data-phone="${formatPhoneNumber(phone)}">
                <div class="contact-avatar me-3">${initials}</div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">${name}</h6>
                    <p class="mb-1 text-muted small">Nova conversa</p>
                    <small class="text-muted">Agora</small>
                </div>
            </div>
        `;
        
        $('#contactsList').prepend(contactHtml);
    }

    function startMessagePolling() {
        // Polling para novas mensagens a cada 5 segundos
        chatInterval = setInterval(() => {
            if (currentContact) {
                // Aqui você faria uma requisição para buscar novas mensagens
                // checkNewMessages(currentContact.phone);
            }
        }, 5000);
    }

    // Formatação de telefone em tempo real
    $('#contactPhone').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            if (value.length < 14) {
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            }
        }
        $(this).val(value);
    });

    // Enviar mensagem com Enter
    $('#messageInput').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Cleanup ao sair da página
    $(window).on('beforeunload', function() {
        if (chatInterval) {
            clearInterval(chatInterval);
        }
    });
});
</script>
@endpush


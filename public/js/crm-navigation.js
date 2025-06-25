/**
 * CRM Médico - Menu e Navegação
 * Sistema de menu interativo e responsivo
 */

class CRMNavigation {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeComponents();
        this.checkNotifications();
        this.updateOnlineStatus();
        
        // Auto-update a cada 30 segundos
        setInterval(() => {
            this.checkNotifications();
            this.updateOnlineStatus();
        }, 30000);
    }

    setupEventListeners() {
        // Toggle sidebar em dispositivos móveis
        $(document).on('click', '.sidebar-toggle', (e) => {
            e.preventDefault();
            this.toggleSidebar();
        });

        // Fechar sidebar ao clicar fora (mobile)
        $(document).on('click', (e) => {
            if (window.innerWidth <= 768) {
                const sidebar = $('.sidebar');
                const toggle = $('.sidebar-toggle');
                
                if (!sidebar.is(e.target) && sidebar.has(e.target).length === 0 && 
                    !toggle.is(e.target) && toggle.has(e.target).length === 0) {
                    this.closeSidebar();
                }
            }
        });

        // Highlight do menu ativo
        this.highlightActiveMenu();

        // Smooth scroll para links internos
        $('a[href^="#"]').on('click', (e) => {
            const target = $(e.currentTarget.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 70
                }, 500);
            }
        });

        // Submenu toggle
        $('.nav-link[data-toggle="submenu"]').on('click', (e) => {
            e.preventDefault();
            this.toggleSubmenu($(e.currentTarget));
        });
    }

    initializeComponents() {
        // Inicializar tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Inicializar popovers
        $('[data-bs-toggle="popover"]').popover();

        // Auto-hide alerts
        $('.alert[data-auto-dismiss]').each(function() {
            const delay = $(this).data('auto-dismiss') || 5000;
            setTimeout(() => {
                $(this).fadeOut();
            }, delay);
        });

        // Progress bars animadas
        $('.progress-bar').each(function() {
            const width = $(this).attr('style').match(/width:\s*(\d+)%/);
            if (width) {
                $(this).css('width', '0%').animate({
                    width: width[0]
                }, 1000);
            }
        });
    }

    toggleSidebar() {
        $('.sidebar').toggleClass('show');
        $('body').toggleClass('sidebar-open');
    }

    closeSidebar() {
        $('.sidebar').removeClass('show');
        $('body').removeClass('sidebar-open');
    }

    highlightActiveMenu() {
        const currentPath = window.location.pathname;
        
        $('.sidebar .nav-link').each(function() {
            const link = $(this);
            const href = link.attr('href');
            
            if (href && currentPath.includes(href.replace(window.location.origin, ''))) {
                link.addClass('active');
                
                // Se estiver em um submenu, abrir o pai
                const submenu = link.closest('.submenu');
                if (submenu.length) {
                    submenu.addClass('show');
                    submenu.prev('.nav-link').addClass('active');
                }
            }
        });
    }

    toggleSubmenu($trigger) {
        const $submenu = $trigger.next('.submenu');
        const isOpen = $submenu.hasClass('show');
        
        // Fechar todos os outros submenus
        $('.submenu').removeClass('show');
        $('.nav-link[data-toggle="submenu"]').removeClass('active');
        
        // Toggle do submenu atual
        if (!isOpen) {
            $submenu.addClass('show');
            $trigger.addClass('active');
        }
    }

    async checkNotifications() {
        try {
            // Simular chamada API para notificações
            const notifications = await this.fetchNotifications();
            this.updateNotificationBadge(notifications.unread);
            this.updateNotificationList(notifications.items);
        } catch (error) {
            console.warn('Erro ao buscar notificações:', error);
        }
    }

    async fetchNotifications() {
        // Simular dados para demonstração
        return new Promise(resolve => {
            setTimeout(() => {
                resolve({
                    unread: Math.floor(Math.random() * 5),
                    items: [
                        {
                            id: 1,
                            type: 'message',
                            title: 'Nova mensagem WhatsApp',
                            content: 'João Silva enviou uma mensagem',
                            time: 'há 5 minutos',
                            icon: 'fab fa-whatsapp',
                            color: 'success'
                        },
                        {
                            id: 2,
                            type: 'deal',
                            title: 'Negócio movido',
                            content: 'Maria Santos - Proposta enviada',
                            time: 'há 15 minutos',
                            icon: 'fas fa-handshake',
                            color: 'info'
                        }
                    ]
                });
            }, 500);
        });
    }

    updateNotificationBadge(count) {
        const badge = $('.notification-badge');
        if (count > 0) {
            badge.text(count).show();
        } else {
            badge.hide();
        }
    }

    updateNotificationList(notifications) {
        const container = $('.notification-list');
        if (!container.length) return;

        container.empty();
        
        if (notifications.length === 0) {
            container.append(`
                <li class="dropdown-item text-center text-muted">
                    <i class="fas fa-bell-slash mb-2"></i><br>
                    Nenhuma notificação
                </li>
            `);
            return;
        }

        notifications.forEach(notification => {
            container.append(`
                <li>
                    <a class="dropdown-item" href="#" data-notification-id="${notification.id}">
                        <div class="d-flex align-items-center">
                            <i class="${notification.icon} text-${notification.color} me-2"></i>
                            <div>
                                <small class="fw-bold">${notification.title}</small><br>
                                <small class="text-muted">${notification.content}</small><br>
                                <small class="text-muted">${notification.time}</small>
                            </div>
                        </div>
                    </a>
                </li>
            `);
        });
    }

    async updateOnlineStatus() {
        try {
            // Verificar status do WhatsApp
            const status = await this.checkWhatsAppStatus();
            this.updateStatusIndicator(status);
        } catch (error) {
            console.warn('Erro ao verificar status:', error);
            this.updateStatusIndicator('offline');
        }
    }

    async checkWhatsAppStatus() {
        // Simular verificação de status
        return new Promise(resolve => {
            setTimeout(() => {
                resolve(Math.random() > 0.2 ? 'online' : 'offline');
            }, 200);
        });
    }

    updateStatusIndicator(status) {
        const indicator = $('.whatsapp-status');
        const badge = indicator.find('.badge');
        
        indicator.removeClass('status-online status-offline');
        indicator.addClass(`status-${status}`);
        
        if (status === 'online') {
            badge.removeClass('bg-danger').addClass('bg-success');
            badge.html('<i class="fab fa-whatsapp me-1"></i> Online');
        } else {
            badge.removeClass('bg-success').addClass('bg-danger');
            badge.html('<i class="fab fa-whatsapp me-1"></i> Offline');
        }
    }

    // Utilitários
    showNotification(message, type = 'success', duration = 5000) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 
                          type === 'warning' ? 'alert-warning' : 'alert-info';
        
        const icon = type === 'success' ? 'fa-check-circle' : 
                    type === 'error' ? 'fa-exclamation-circle' : 
                    type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
        
        const alert = $(`
            <div class="alert ${alertClass} alert-dismissible fade show notification-alert" role="alert">
                <i class="fas ${icon} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('.main-content .p-4').prepend(alert);
        
        // Auto remove
        setTimeout(() => {
            alert.fadeOut(() => alert.remove());
        }, duration);
    }

    showLoader() {
        $('body').append(`
            <div class="loader-overlay">
                <div class="loader-content">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-2">Carregando...</p>
                </div>
            </div>
        `);
    }

    hideLoader() {
        $('.loader-overlay').fadeOut(() => $('.loader-overlay').remove());
    }

    confirmAction(message, callback) {
        if (confirm(message)) {
            callback();
        }
    }

    formatPhone(phone) {
        const cleaned = phone.replace(/\D/g, '');
        if (cleaned.length === 11) {
            return cleaned.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        } else if (cleaned.length === 10) {
            return cleaned.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        }
        return phone;
    }

    formatCurrency(value) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    }

    formatDate(date) {
        return new Date(date).toLocaleDateString('pt-BR');
    }

    formatDateTime(date) {
        return new Date(date).toLocaleString('pt-BR');
    }
}

// Utilitários globais para o CRM
window.CRM = {
    navigation: null,
    
    init() {
        this.navigation = new CRMNavigation();
        
        // Configurar AJAX globalmente
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: () => {
                this.navigation.showLoader();
            },
            complete: () => {
                this.navigation.hideLoader();
            },
            error: (xhr, status, error) => {
                console.error('Erro AJAX:', error);
                this.navigation.showNotification('Erro na requisição. Tente novamente.', 'error');
            }
        });
    },
    
    notify: (message, type, duration) => {
        if (window.CRM.navigation) {
            window.CRM.navigation.showNotification(message, type, duration);
        }
    },
    
    confirm: (message, callback) => {
        if (window.CRM.navigation) {
            window.CRM.navigation.confirmAction(message, callback);
        }
    },
    
    formatPhone: (phone) => {
        if (window.CRM.navigation) {
            return window.CRM.navigation.formatPhone(phone);
        }
        return phone;
    },
    
    formatCurrency: (value) => {
        if (window.CRM.navigation) {
            return window.CRM.navigation.formatCurrency(value);
        }
        return value;
    }
};

// Inicializar quando o DOM estiver pronto
$(document).ready(() => {
    window.CRM.init();
});

// Adicionar estilos CSS inline para o loader
$('<style>').text(`
    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    
    .loader-content {
        background: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .notification-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1055;
        min-width: 300px;
        animation: slideInRight 0.3s ease-out;
    }
`).appendTo('head');

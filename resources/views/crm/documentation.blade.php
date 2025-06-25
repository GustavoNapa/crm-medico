@extends('layouts.crm')

@section('title', 'Documentação - CRM Médico')
@section('page-title', 'Documentação do Sistema')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-list me-2"></i>Índice</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="#introduction" class="list-group-item list-group-item-action">
                        <i class="fas fa-home me-2"></i>Introdução
                    </a>
                    <a href="#dashboard" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="#contacts" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2"></i>Gestão de Contatos
                    </a>
                    <a href="#kanban" class="list-group-item list-group-item-action">
                        <i class="fas fa-columns me-2"></i>Funil de Vendas
                    </a>
                    <a href="#whatsapp" class="list-group-item list-group-item-action">
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                    </a>
                    <a href="#webhooks" class="list-group-item list-group-item-action">
                        <i class="fas fa-plug me-2"></i>Webhooks
                    </a>
                    <a href="#reports" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar me-2"></i>Relatórios
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <!-- Introdução -->
                <section id="introduction" class="mb-5">
                    <h2 class="text-primary mb-3">
                        <i class="fas fa-heartbeat me-2"></i>Bem-vindo ao CRM Médico
                    </h2>
                    <p class="lead">
                        O CRM Médico é uma solução completa para gestão de relacionamento com pacientes, 
                        desenvolvida especificamente para profissionais da área da saúde.
                    </p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Dica:</strong> Use esta documentação como referência para aproveitar ao máximo 
                        todas as funcionalidades do sistema.
                    </div>
                </section>

                <!-- Dashboard -->
                <section id="dashboard" class="mb-5">
                    <h3 class="text-primary mb-3">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </h3>
                    <p>
                        O Dashboard é a página inicial do sistema, onde você encontra um resumo 
                        das principais informações e métricas do seu negócio.
                    </p>
                    <h5>Funcionalidades:</h5>
                    <ul>
                        <li><strong>Cards de Estatísticas:</strong> Visão geral de contatos, negócios ativos, mensagens e taxa de conversão</li>
                        <li><strong>Gráfico de Vendas:</strong> Visualização das vendas dos últimos 30 dias</li>
                        <li><strong>Funil de Vendas:</strong> Resumo dos negócios em cada etapa</li>
                        <li><strong>Atividades Recentes:</strong> Timeline das últimas ações no sistema</li>
                        <li><strong>Mensagens Pendentes:</strong> Lista de conversas que precisam de atenção</li>
                    </ul>
                    <div class="alert alert-success">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Dica:</strong> O Dashboard é atualizado em tempo real. Use-o para monitorar 
                        o desempenho diário do seu consultório.
                    </div>
                </section>

                <!-- Contatos -->
                <section id="contacts" class="mb-5">
                    <h3 class="text-primary mb-3">
                        <i class="fas fa-users me-2"></i>Gestão de Contatos
                    </h3>
                    <p>
                        O módulo de Contatos permite gerenciar todos os seus pacientes e leads 
                        de forma organizada e eficiente.
                    </p>
                    <h5>Como usar:</h5>
                    <ol>
                        <li><strong>Adicionar Contato:</strong> Clique em "Novo Contato" e preencha as informações</li>
                        <li><strong>Filtrar:</strong> Use os filtros por status, origem ou busca por nome</li>
                        <li><strong>Visualizar:</strong> Alterne entre visualização em cards ou lista</li>
                        <li><strong>Ações:</strong> Para cada contato, você pode enviar WhatsApp, editar ou ver histórico</li>
                    </ol>
                    <h5>Status dos Contatos:</h5>
                    <ul>
                        <li><span class="badge bg-info me-2">Lead</span> - Interessado inicial</li>
                        <li><span class="badge bg-warning text-dark me-2">Prospect</span> - Contato qualificado</li>
                        <li><span class="badge bg-success me-2">Cliente</span> - Paciente ativo</li>
                        <li><span class="badge bg-secondary me-2">Inativo</span> - Sem atividade recente</li>
                    </ul>
                </section>

                <!-- Kanban -->
                <section id="kanban" class="mb-5">
                    <h3 class="text-primary mb-3">
                        <i class="fas fa-columns me-2"></i>Funil de Vendas (Kanban)
                    </h3>
                    <p>
                        O sistema Kanban permite visualizar e gerenciar o processo de vendas 
                        de forma visual e intuitiva.
                    </p>
                    <h5>Etapas Padrão:</h5>
                    <ol>
                        <li><strong>Prospecção:</strong> Leads iniciais e primeiros contatos</li>
                        <li><strong>Qualificação:</strong> Leads que demonstraram interesse real</li>
                        <li><strong>Proposta:</strong> Orçamentos e propostas enviadas</li>
                        <li><strong>Negociação:</strong> Discussão de valores e condições</li>
                        <li><strong>Fechamento:</strong> Negócios prontos para finalizar</li>
                    </ol>
                    <h5>Como usar:</h5>
                    <ul>
                        <li>Arraste e solte os cards entre as colunas</li>
                        <li>Clique em um card para ver detalhes</li>
                        <li>Use as ações para marcar como ganho ou perdido</li>
                        <li>Acompanhe as estatísticas em tempo real</li>
                    </ul>
                </section>

                <!-- WhatsApp -->
                <section id="whatsapp" class="mb-5">
                    <h3 class="text-primary mb-3">
                        <i class="fab fa-whatsapp me-2"></i>Integração WhatsApp
                    </h3>
                    <p>
                        A integração com WhatsApp permite enviar e receber mensagens diretamente 
                        do sistema, mantendo todo o histórico organizado.
                    </p>
                    <h5>Configuração Inicial:</h5>
                    <ol>
                        <li>Acesse "Config WhatsApp" no menu</li>
                        <li>Configure a URL da Evolution API</li>
                        <li>Insira o token de acesso</li>
                        <li>Escaneie o QR Code para conectar</li>
                        <li>Configure os webhooks</li>
                    </ol>
                    <h5>Funcionalidades:</h5>
                    <ul>
                        <li>Envio de mensagens de texto</li>
                        <li>Envio de imagens e documentos</li>
                        <li>Histórico completo de conversas</li>
                        <li>Status de entrega e leitura</li>
                        <li>Notificações em tempo real</li>
                    </ul>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong> Certifique-se de que sua Evolution API está 
                        configurada corretamente para evitar problemas de conectividade.
                    </div>
                </section>

                <!-- Webhooks -->
                <section id="webhooks" class="mb-5">
                    <h3 class="text-primary mb-3">
                        <i class="fas fa-plug me-2"></i>Sistema de Webhooks
                    </h3>
                    <p>
                        Os webhooks permitem integrar o CRM com outros sistemas de forma automática, 
                        sincronizando dados em tempo real.
                    </p>
                    <h5>Tipos de Mapeamento:</h5>
                    <ul>
                        <li><strong>Evolution API:</strong> Processa mensagens do WhatsApp</li>
                        <li><strong>CRM Integration:</strong> Sincroniza com sistemas externos</li>
                        <li><strong>Email Marketing:</strong> Adiciona contatos a listas de email</li>
                    </ul>
                    <h5>Como criar um mapeamento:</h5>
                    <ol>
                        <li>Clique em "Novo Mapeamento"</li>
                        <li>Defina nome e descrição</li>
                        <li>Configure as condições de aplicação</li>
                        <li>Mapeie os campos de origem para destino</li>
                        <li>Defina as transformações necessárias</li>
                        <li>Teste o mapeamento antes de ativar</li>
                    </ol>
                </section>

                <!-- Relatórios -->
                <section id="reports" class="mb-5">
                    <h3 class="text-primary mb-3">
                        <i class="fas fa-chart-bar me-2"></i>Relatórios e Histórico
                    </h3>
                    <p>
                        O sistema oferece relatórios detalhados e histórico completo de todas 
                        as interações com contatos e negócios.
                    </p>
                    <h5>Tipos de Relatório:</h5>
                    <ul>
                        <li><strong>Histórico de Contato:</strong> Timeline completa de interações</li>
                        <li><strong>Jornada do Negócio:</strong> Todas as etapas de um deal</li>
                        <li><strong>Estatísticas:</strong> Métricas de desempenho</li>
                        <li><strong>Atividades Recentes:</strong> Últimas ações no sistema</li>
                    </ul>
                    <h5>Exportação:</h5>
                    <p>
                        Todos os relatórios podem ser exportados em formato JSON ou CSV 
                        para análise externa ou backup.
                    </p>
                </section>

                <!-- Suporte -->
                <section id="support" class="mb-5">
                    <h3 class="text-primary mb-3">
                        <i class="fas fa-headset me-2"></i>Suporte e Ajuda
                    </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                                    <h5>Email</h5>
                                    <p>suporte@crmmedico.com</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fab fa-whatsapp fa-3x text-success mb-3"></i>
                                    <h5>WhatsApp</h5>
                                    <p>(11) 99999-9999</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Smooth scroll para links do índice
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
            
            // Atualizar link ativo
            $('.list-group-item').removeClass('active');
            $(this).addClass('active');
        }
    });
    
    // Highlight da seção visível durante scroll
    $(window).on('scroll', function() {
        let current = '';
        $('section[id]').each(function() {
            const sectionTop = $(this).offset().top;
            if ($(window).scrollTop() >= sectionTop - 150) {
                current = $(this).attr('id');
            }
        });
        
        $('.list-group-item').removeClass('active');
        $(`a[href="#${current}"]`).addClass('active');
    });
});
</script>
@endpush

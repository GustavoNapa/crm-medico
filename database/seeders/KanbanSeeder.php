<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pipeline;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\User;

class KanbanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar pipelines baseados nos exemplos fornecidos
        $pipelines = [
            [
                'name' => 'Prospecção',
                'description' => 'Leads iniciais e primeiros contatos',
                'color' => '#ffc107',
                'position' => 1
            ],
            [
                'name' => 'Qualificação',
                'description' => 'Leads qualificados e interessados',
                'color' => '#17a2b8',
                'position' => 2
            ],
            [
                'name' => 'Proposta',
                'description' => 'Propostas enviadas e em análise',
                'color' => '#6f42c1',
                'position' => 3
            ],
            [
                'name' => 'Negociação',
                'description' => 'Em processo de negociação',
                'color' => '#fd7e14',
                'position' => 4
            ],
            [
                'name' => 'Fechamento',
                'description' => 'Pronto para fechar',
                'color' => '#28a745',
                'position' => 5
            ]
        ];

        foreach ($pipelines as $pipelineData) {
            Pipeline::create($pipelineData);
        }

        // Criar contatos de exemplo
        $contacts = [
            [
                'name' => 'João da Silva',
                'email' => 'joao.silva@email.com',
                'phone' => '11999999999',
                'whatsapp' => '11999999999',
                'birth_date' => '1985-03-15',
                'city' => 'São Paulo',
                'state' => 'SP',
                'source' => 'WhatsApp',
                'potential_value' => 500.00,
                'last_contact' => now()->subHours(2)
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@email.com',
                'phone' => '11888888888',
                'whatsapp' => '11888888888',
                'birth_date' => '1990-07-22',
                'city' => 'São Paulo',
                'state' => 'SP',
                'source' => 'Indicação',
                'potential_value' => 800.00,
                'last_contact' => now()->subHours(5)
            ],
            [
                'name' => 'Pedro Costa',
                'email' => 'pedro.costa@email.com',
                'phone' => '11777777777',
                'whatsapp' => '11777777777',
                'birth_date' => '1978-12-10',
                'city' => 'São Paulo',
                'state' => 'SP',
                'source' => 'Site',
                'potential_value' => 1200.00,
                'last_contact' => now()->subDay()
            ],
            [
                'name' => 'Ana Paula',
                'email' => 'ana.paula@email.com',
                'phone' => '11666666666',
                'whatsapp' => '11666666666',
                'birth_date' => '1992-05-18',
                'city' => 'São Paulo',
                'state' => 'SP',
                'source' => 'WhatsApp',
                'potential_value' => 350.00,
                'last_contact' => now()->subHours(1)
            ],
            [
                'name' => 'Carlos Oliveira',
                'email' => 'carlos.oliveira@email.com',
                'phone' => '11555555555',
                'whatsapp' => '11555555555',
                'birth_date' => '1980-09-25',
                'city' => 'São Paulo',
                'state' => 'SP',
                'source' => 'Indicação',
                'potential_value' => 950.00,
                'last_contact' => now()->subHours(8)
            ]
        ];

        foreach ($contacts as $contactData) {
            Contact::create($contactData);
        }

        // Obter o primeiro usuário (assumindo que já existe)
        $user = User::first();
        if (!$user) {
            // Se não existir usuário, criar um
            $user = User::create([
                'name' => 'Dr. João Silva',
                'email' => 'dr.joao@clinica.com',
                'password' => bcrypt('123456789')
            ]);
        }

        // Criar deals de exemplo
        $deals = [
            [
                'title' => 'Consulta de Rotina - João',
                'description' => 'Consulta médica de rotina para check-up geral',
                'contact_id' => 1,
                'pipeline_id' => 1, // Prospecção
                'user_id' => $user->id,
                'value' => 150.00,
                'expected_close_date' => now()->addDays(7),
                'position' => 1,
                'last_activity' => now()->subHours(2)
            ],
            [
                'title' => 'Exame Cardiológico - Maria',
                'description' => 'Exame cardiológico completo com eletrocardiograma',
                'contact_id' => 2,
                'pipeline_id' => 2, // Qualificação
                'user_id' => $user->id,
                'value' => 300.00,
                'expected_close_date' => now()->addDays(10),
                'position' => 1,
                'last_activity' => now()->subHours(5)
            ],
            [
                'title' => 'Cirurgia Menor - Pedro',
                'description' => 'Procedimento cirúrgico de pequeno porte',
                'contact_id' => 3,
                'pipeline_id' => 3, // Proposta
                'user_id' => $user->id,
                'value' => 1200.00,
                'expected_close_date' => now()->addDays(15),
                'position' => 1,
                'last_activity' => now()->subDay()
            ],
            [
                'title' => 'Consulta Dermatológica - Ana',
                'description' => 'Consulta especializada em dermatologia',
                'contact_id' => 4,
                'pipeline_id' => 1, // Prospecção
                'user_id' => $user->id,
                'value' => 200.00,
                'expected_close_date' => now()->addDays(5),
                'position' => 2,
                'last_activity' => now()->subHours(1)
            ],
            [
                'title' => 'Tratamento Fisioterapia - Carlos',
                'description' => 'Sessões de fisioterapia para reabilitação',
                'contact_id' => 5,
                'pipeline_id' => 4, // Negociação
                'user_id' => $user->id,
                'value' => 800.00,
                'expected_close_date' => now()->addDays(20),
                'position' => 1,
                'last_activity' => now()->subHours(8)
            ]
        ];

        foreach ($deals as $dealData) {
            Deal::create($dealData);
        }
    }
}


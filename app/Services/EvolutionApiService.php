<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EvolutionApiService
{
    private $baseUrl;
    private $apiKey;
    private $instanceName;

    public function __construct()
    {
        $this->baseUrl = config('evolution.base_url', 'http://localhost:8080');
        $this->apiKey = config('evolution.api_key');
        $this->instanceName = config('evolution.instance_name', 'crm-medico');
    }

    /**
     * Criar uma nova instância do WhatsApp
     */
    public function createInstance($instanceName = null)
    {
        $instance = $instanceName ?? $this->instanceName;
        
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post("{$this->baseUrl}/instance/create", [
                'instanceName' => $instance,
                'token' => $this->apiKey,
                'qrcode' => true,
                'markMessagesRead' => true,
                'delayMessage' => 1000,
                'msgRetryCounterValue' => 3,
                'alwaysOnline' => true,
                'presence' => 'available'
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Erro ao criar instância Evolution API: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obter QR Code para conectar WhatsApp
     */
    public function getQrCode($instanceName = null)
    {
        $instance = $instanceName ?? $this->instanceName;
        
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey
            ])->get("{$this->baseUrl}/instance/connect/{$instance}");

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Erro ao obter QR Code: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verificar status da instância
     */
    public function getInstanceStatus($instanceName = null)
    {
        $instance = $instanceName ?? $this->instanceName;
        
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey
            ])->get("{$this->baseUrl}/instance/connectionState/{$instance}");

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Erro ao verificar status da instância: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar mensagem de texto
     */
    public function sendTextMessage($number, $message, $instanceName = null)
    {
        $instance = $instanceName ?? $this->instanceName;
        
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post("{$this->baseUrl}/message/sendText/{$instance}", [
                'number' => $this->formatPhoneNumber($number),
                'text' => $message
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Erro ao enviar mensagem: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar mensagem com mídia
     */
    public function sendMediaMessage($number, $mediaUrl, $caption = '', $instanceName = null)
    {
        $instance = $instanceName ?? $this->instanceName;
        
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post("{$this->baseUrl}/message/sendMedia/{$instance}", [
                'number' => $this->formatPhoneNumber($number),
                'mediatype' => 'image',
                'media' => $mediaUrl,
                'caption' => $caption
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Erro ao enviar mídia: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obter mensagens recebidas
     */
    public function getMessages($instanceName = null)
    {
        $instance = $instanceName ?? $this->instanceName;
        
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey
            ])->get("{$this->baseUrl}/chat/findMessages/{$instance}");

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Erro ao obter mensagens: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Configurar webhook para receber mensagens
     */
    public function setWebhook($webhookUrl, $instanceName = null)
    {
        $instance = $instanceName ?? $this->instanceName;
        
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post("{$this->baseUrl}/webhook/set/{$instance}", [
                'url' => $webhookUrl,
                'enabled' => true,
                'events' => [
                    'APPLICATION_STARTUP',
                    'QRCODE_UPDATED',
                    'MESSAGES_UPSERT',
                    'MESSAGES_UPDATE',
                    'MESSAGES_DELETE',
                    'SEND_MESSAGE',
                    'CONTACTS_SET',
                    'CONTACTS_UPSERT',
                    'CONTACTS_UPDATE',
                    'PRESENCE_UPDATE',
                    'CHATS_SET',
                    'CHATS_UPSERT',
                    'CHATS_UPDATE',
                    'CHATS_DELETE',
                    'GROUPS_UPSERT',
                    'GROUP_UPDATE',
                    'GROUP_PARTICIPANTS_UPDATE',
                    'CONNECTION_UPDATE'
                ]
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Erro ao configurar webhook: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Formatar número de telefone para o padrão internacional
     */
    private function formatPhoneNumber($number)
    {
        // Remove todos os caracteres não numéricos
        $number = preg_replace('/[^0-9]/', '', $number);
        
        // Se não começar com código do país, adiciona o código do Brasil (55)
        if (strlen($number) == 11 && !str_starts_with($number, '55')) {
            $number = '55' . $number;
        }
        
        return $number . '@s.whatsapp.net';
    }

    /**
     * Verificar se um número tem WhatsApp
     */
    public function checkWhatsApp($number, $instanceName = null)
    {
        $instance = $instanceName ?? $this->instanceName;
        
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post("{$this->baseUrl}/chat/whatsappNumbers/{$instance}", [
                'numbers' => [$this->formatPhoneNumber($number)]
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Erro ao verificar WhatsApp: ' . $e->getMessage());
            return false;
        }
    }
}


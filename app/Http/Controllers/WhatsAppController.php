<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EvolutionApiService;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    protected $evolutionApi;

    public function __construct(EvolutionApiService $evolutionApi)
    {
        $this->evolutionApi = $evolutionApi;
    }

    /**
     * Página principal do WhatsApp
     */
    public function index()
    {
        $status = $this->evolutionApi->getInstanceStatus();
        return view('whatsapp.index', compact('status'));
    }

    /**
     * Criar nova instância
     */
    public function createInstance(Request $request)
    {
        $instanceName = $request->input('instance_name', config('evolution.instance_name'));
        $result = $this->evolutionApi->createInstance($instanceName);
        
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Instância criada com sucesso',
                'data' => $result
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Erro ao criar instância'
        ], 500);
    }

    /**
     * Obter QR Code
     */
    public function getQrCode(Request $request)
    {
        $instanceName = $request->input('instance_name', config('evolution.instance_name'));
        $result = $this->evolutionApi->getQrCode($instanceName);
        
        if ($result) {
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Erro ao obter QR Code'
        ], 500);
    }

    /**
     * Verificar status da conexão
     */
    public function getStatus(Request $request)
    {
        $instanceName = $request->input('instance_name', config('evolution.instance_name'));
        $result = $this->evolutionApi->getInstanceStatus($instanceName);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Enviar mensagem de texto
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'number' => 'required|string',
            'message' => 'required|string'
        ]);

        $number = $request->input('number');
        $message = $request->input('message');
        $instanceName = $request->input('instance_name', config('evolution.instance_name'));

        $result = $this->evolutionApi->sendTextMessage($number, $message, $instanceName);
        
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Mensagem enviada com sucesso',
                'data' => $result
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Erro ao enviar mensagem'
        ], 500);
    }

    /**
     * Enviar mensagem com mídia
     */
    public function sendMedia(Request $request)
    {
        $request->validate([
            'number' => 'required|string',
            'media_url' => 'required|url',
            'caption' => 'nullable|string'
        ]);

        $number = $request->input('number');
        $mediaUrl = $request->input('media_url');
        $caption = $request->input('caption', '');
        $instanceName = $request->input('instance_name', config('evolution.instance_name'));

        $result = $this->evolutionApi->sendMediaMessage($number, $mediaUrl, $caption, $instanceName);
        
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Mídia enviada com sucesso',
                'data' => $result
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Erro ao enviar mídia'
        ], 500);
    }

    /**
     * Obter mensagens
     */
    public function getMessages(Request $request)
    {
        $instanceName = $request->input('instance_name', config('evolution.instance_name'));
        $result = $this->evolutionApi->getMessages($instanceName);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Configurar webhook
     */
    public function setWebhook(Request $request)
    {
        $request->validate([
            'webhook_url' => 'required|url'
        ]);

        $webhookUrl = $request->input('webhook_url');
        $instanceName = $request->input('instance_name', config('evolution.instance_name'));

        $result = $this->evolutionApi->setWebhook($webhookUrl, $instanceName);
        
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Webhook configurado com sucesso',
                'data' => $result
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Erro ao configurar webhook'
        ], 500);
    }

    /**
     * Receber webhook da Evolution API
     */
    public function receiveWebhook(Request $request)
    {
        $data = $request->all();
        
        // Log do webhook recebido
        Log::info('Webhook recebido da Evolution API:', $data);
        
        // Processar diferentes tipos de eventos
        if (isset($data['event'])) {
            switch ($data['event']) {
                case 'messages.upsert':
                    $this->processNewMessage($data);
                    break;
                case 'connection.update':
                    $this->processConnectionUpdate($data);
                    break;
                case 'qrcode.updated':
                    $this->processQrCodeUpdate($data);
                    break;
                default:
                    Log::info('Evento não processado: ' . $data['event']);
            }
        }
        
        return response()->json(['status' => 'received']);
    }

    /**
     * Processar nova mensagem recebida
     */
    private function processNewMessage($data)
    {
        if (isset($data['data']['messages'])) {
            foreach ($data['data']['messages'] as $message) {
                // Aqui você pode salvar a mensagem no banco de dados
                // e processar conforme necessário
                Log::info('Nova mensagem recebida:', $message);
                
                // Exemplo: salvar no banco de dados
                // WhatsAppMessage::create([
                //     'instance_name' => $data['instance'],
                //     'message_id' => $message['key']['id'],
                //     'from' => $message['key']['remoteJid'],
                //     'message_type' => $message['messageType'],
                //     'content' => $message['message']['conversation'] ?? '',
                //     'timestamp' => $message['messageTimestamp'],
                //     'received_at' => now()
                // ]);
            }
        }
    }

    /**
     * Processar atualização de conexão
     */
    private function processConnectionUpdate($data)
    {
        Log::info('Atualização de conexão:', $data);
        
        // Aqui você pode atualizar o status da conexão no banco de dados
        // ou notificar o usuário sobre mudanças no status
    }

    /**
     * Processar atualização do QR Code
     */
    private function processQrCodeUpdate($data)
    {
        Log::info('QR Code atualizado:', $data);
        
        // Aqui você pode notificar o frontend sobre o novo QR Code
        // via WebSocket ou outra forma de comunicação em tempo real
    }

    /**
     * Verificar se número tem WhatsApp
     */
    public function checkWhatsApp(Request $request)
    {
        $request->validate([
            'number' => 'required|string'
        ]);

        $number = $request->input('number');
        $instanceName = $request->input('instance_name', config('evolution.instance_name'));

        $result = $this->evolutionApi->checkWhatsApp($number, $instanceName);
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}


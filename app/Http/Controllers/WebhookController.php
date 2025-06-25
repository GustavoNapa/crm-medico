<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebhookMapping;
use App\Models\WebhookLog;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\WhatsAppMessage;
use App\Models\Activity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WebhookController extends Controller
{
    /**
     * Receber webhook genérico
     */
    public function receiveWebhook(Request $request, $source = 'generic')
    {
        try {
            // Log do webhook recebido
            $webhookLog = WebhookLog::create([
                'source' => $source,
                'event_type' => $request->input('event') ?? 'unknown',
                'webhook_id' => $request->input('id') ?? uniqid(),
                'payload' => $request->all(),
                'headers' => $request->headers->all(),
                'received_at' => now(),
                'status' => 'pending'
            ]);

            // Processar webhook
            $result = $this->processWebhook($webhookLog);

            return response()->json([
                'success' => true,
                'message' => 'Webhook processado com sucesso',
                'webhook_id' => $webhookLog->id,
                'processed' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook: ' . $e->getMessage(), [
                'source' => $source,
                'payload' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar webhook: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Processar webhook baseado nos mapeamentos
     */
    private function processWebhook(WebhookLog $webhookLog)
    {
        try {
            $webhookLog->update(['status' => 'processing']);

            $payload = $webhookLog->payload;
            $eventType = $webhookLog->event_type;
            $source = $webhookLog->source;

            // Buscar mapeamentos ativos para este tipo de evento
            $mappings = WebhookMapping::where('event_type', $eventType)
                ->where('source', $source)
                ->where('is_active', true)
                ->get();

            if ($mappings->isEmpty()) {
                // Tentar mapeamentos genéricos
                $mappings = WebhookMapping::where('event_type', 'any')
                    ->where('source', $source)
                    ->where('is_active', true)
                    ->get();
            }

            $appliedMappings = [];
            $processedData = [];

            foreach ($mappings as $mapping) {
                try {
                    $result = $this->applyMapping($mapping, $payload);
                    if ($result) {
                        $appliedMappings[] = [
                            'mapping_id' => $mapping->id,
                            'mapping_name' => $mapping->name,
                            'result' => $result
                        ];
                        $processedData = array_merge($processedData, $result);
                    }
                } catch (\Exception $e) {
                    Log::error('Erro ao aplicar mapeamento: ' . $e->getMessage(), [
                        'mapping_id' => $mapping->id,
                        'webhook_id' => $webhookLog->id
                    ]);
                }
            }

            $webhookLog->update([
                'status' => 'success',
                'processed_at' => now(),
                'applied_mappings' => $appliedMappings,
                'processed_data' => $processedData
            ]);

            return $processedData;

        } catch (\Exception $e) {
            $webhookLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'processed_at' => now()
            ]);

            throw $e;
        }
    }

    /**
     * Aplicar mapeamento específico
     */
    private function applyMapping(WebhookMapping $mapping, array $payload)
    {
        // Verificar condições
        if ($mapping->conditions && !$this->checkConditions($mapping->conditions, $payload)) {
            return null;
        }

        // Extrair valor do webhook
        $value = $this->extractValue($payload, $mapping->webhook_field);
        
        if ($value === null) {
            return null;
        }

        // Aplicar transformações
        if ($mapping->transformations) {
            $value = $this->applyTransformations($value, $mapping->transformations);
        }

        // Aplicar ao modelo de destino
        return $this->updateTargetModel($mapping, $value, $payload);
    }

    /**
     * Verificar condições do mapeamento
     */
    private function checkConditions(array $conditions, array $payload)
    {
        foreach ($conditions as $condition) {
            $field = $condition['field'] ?? '';
            $operator = $condition['operator'] ?? '=';
            $expectedValue = $condition['value'] ?? '';

            $actualValue = $this->extractValue($payload, $field);

            switch ($operator) {
                case '=':
                case '==':
                    if ($actualValue != $expectedValue) return false;
                    break;
                case '!=':
                    if ($actualValue == $expectedValue) return false;
                    break;
                case 'contains':
                    if (strpos($actualValue, $expectedValue) === false) return false;
                    break;
                case 'not_contains':
                    if (strpos($actualValue, $expectedValue) !== false) return false;
                    break;
                case 'exists':
                    if ($actualValue === null) return false;
                    break;
                case 'not_exists':
                    if ($actualValue !== null) return false;
                    break;
            }
        }

        return true;
    }

    /**
     * Extrair valor do payload usando notação de ponto
     */
    private function extractValue(array $payload, string $field)
    {
        $keys = explode('.', $field);
        $value = $payload;

        foreach ($keys as $key) {
            if (is_array($value) && isset($value[$key])) {
                $value = $value[$key];
            } else {
                return null;
            }
        }

        return $value;
    }

    /**
     * Aplicar transformações ao valor
     */
    private function applyTransformations($value, array $transformations)
    {
        foreach ($transformations as $transformation) {
            $type = $transformation['type'] ?? '';

            switch ($type) {
                case 'uppercase':
                    $value = strtoupper($value);
                    break;
                case 'lowercase':
                    $value = strtolower($value);
                    break;
                case 'trim':
                    $value = trim($value);
                    break;
                case 'phone_format':
                    $value = preg_replace('/\D/', '', $value);
                    break;
                case 'date_format':
                    $format = $transformation['format'] ?? 'Y-m-d H:i:s';
                    $value = Carbon::parse($value)->format($format);
                    break;
                case 'replace':
                    $search = $transformation['search'] ?? '';
                    $replace = $transformation['replace'] ?? '';
                    $value = str_replace($search, $replace, $value);
                    break;
                case 'regex':
                    $pattern = $transformation['pattern'] ?? '';
                    $replacement = $transformation['replacement'] ?? '';
                    $value = preg_replace($pattern, $replacement, $value);
                    break;
                case 'default':
                    if (empty($value)) {
                        $value = $transformation['value'] ?? '';
                    }
                    break;
            }
        }

        return $value;
    }

    /**
     * Atualizar modelo de destino
     */
    private function updateTargetModel(WebhookMapping $mapping, $value, array $payload)
    {
        $modelClass = 'App\\Models\\' . $mapping->target_model;
        
        if (!class_exists($modelClass)) {
            throw new \Exception("Modelo {$mapping->target_model} não encontrado");
        }

        // Determinar como encontrar o registro
        $identifier = $this->findModelIdentifier($mapping, $payload);
        
        if (!$identifier) {
            // Criar novo registro se não encontrar
            return $this->createNewRecord($modelClass, $mapping, $value, $payload);
        }

        // Atualizar registro existente
        return $this->updateExistingRecord($modelClass, $identifier, $mapping, $value);
    }

    /**
     * Encontrar identificador do modelo
     */
    private function findModelIdentifier(WebhookMapping $mapping, array $payload)
    {
        $modelClass = 'App\\Models\\' . $mapping->target_model;

        // Estratégias para encontrar o registro
        switch ($mapping->target_model) {
            case 'Contact':
                // Tentar por telefone/WhatsApp
                $phone = $this->extractValue($payload, 'from') ?? $this->extractValue($payload, 'phone');
                if ($phone) {
                    $phone = preg_replace('/\D/', '', $phone);
                    $contact = Contact::where('whatsapp', 'like', '%' . substr($phone, -9) . '%')
                                   ->orWhere('phone', 'like', '%' . substr($phone, -9) . '%')
                                   ->first();
                    return $contact?->id;
                }
                break;

            case 'Deal':
                // Tentar por ID ou título
                $dealId = $this->extractValue($payload, 'deal_id');
                if ($dealId) {
                    return $dealId;
                }
                break;

            case 'WhatsAppMessage':
                // Tentar por message_id
                $messageId = $this->extractValue($payload, 'key.id') ?? $this->extractValue($payload, 'message_id');
                if ($messageId) {
                    $message = WhatsAppMessage::where('message_id', $messageId)->first();
                    return $message?->id;
                }
                break;
        }

        return null;
    }

    /**
     * Criar novo registro
     */
    private function createNewRecord($modelClass, WebhookMapping $mapping, $value, array $payload)
    {
        $data = [$mapping->target_field => $value];

        // Adicionar campos obrigatórios baseado no modelo
        switch ($mapping->target_model) {
            case 'Contact':
                $phone = $this->extractValue($payload, 'from') ?? $this->extractValue($payload, 'phone');
                if ($phone) {
                    $phone = preg_replace('/\D/', '', $phone);
                    $data['whatsapp'] = $phone;
                    $data['phone'] = $phone;
                    $data['name'] = $value; // Assumir que o valor é o nome
                    $data['source'] = 'webhook';
                }
                break;

            case 'WhatsAppMessage':
                $data = array_merge($data, [
                    'instance_name' => $this->extractValue($payload, 'instance') ?? 'default',
                    'message_id' => $this->extractValue($payload, 'key.id') ?? uniqid(),
                    'from' => $this->extractValue($payload, 'key.remoteJid') ?? '',
                    'direction' => 'inbound',
                    'message_type' => $this->extractValue($payload, 'messageType') ?? 'text',
                    'content' => $value,
                    'message_timestamp' => now()
                ]);
                break;

            case 'Activity':
                $data = array_merge($data, [
                    'type' => 'webhook',
                    'title' => 'Atividade via Webhook',
                    'user_id' => 1, // Usuário padrão
                    'activity_date' => now()
                ]);
                break;
        }

        try {
            $record = $modelClass::create($data);
            return ['action' => 'created', 'id' => $record->id, 'data' => $data];
        } catch (\Exception $e) {
            Log::error('Erro ao criar registro: ' . $e->getMessage(), [
                'model' => $mapping->target_model,
                'data' => $data
            ]);
            return null;
        }
    }

    /**
     * Atualizar registro existente
     */
    private function updateExistingRecord($modelClass, $id, WebhookMapping $mapping, $value)
    {
        try {
            $record = $modelClass::find($id);
            if (!$record) {
                return null;
            }

            $record->update([$mapping->target_field => $value]);
            return ['action' => 'updated', 'id' => $id, 'field' => $mapping->target_field, 'value' => $value];
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar registro: ' . $e->getMessage(), [
                'model' => $mapping->target_model,
                'id' => $id,
                'field' => $mapping->target_field
            ]);
            return null;
        }
    }

    /**
     * Obter mapeamentos
     */
    public function getMappings(Request $request)
    {
        $mappings = WebhookMapping::when($request->source, function ($query, $source) {
                return $query->where('source', $source);
            })
            ->when($request->event_type, function ($query, $eventType) {
                return $query->where('event_type', $eventType);
            })
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'mappings' => $mappings
        ]);
    }

    /**
     * Criar mapeamento
     */
    public function createMapping(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'event_type' => 'required|string',
            'source' => 'required|string',
            'target_model' => 'required|string',
            'target_field' => 'required|string',
            'webhook_field' => 'required|string',
            'field_type' => 'string',
            'conditions' => 'nullable|array',
            'transformations' => 'nullable|array',
            'description' => 'nullable|string'
        ]);

        try {
            $mapping = WebhookMapping::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Mapeamento criado com sucesso',
                'mapping' => $mapping
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar mapeamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar mapeamento
     */
    public function updateMapping(Request $request, $id)
    {
        $mapping = WebhookMapping::findOrFail($id);

        $request->validate([
            'name' => 'string|max:255',
            'event_type' => 'string',
            'source' => 'string',
            'target_model' => 'string',
            'target_field' => 'string',
            'webhook_field' => 'string',
            'field_type' => 'string',
            'conditions' => 'nullable|array',
            'transformations' => 'nullable|array',
            'is_active' => 'boolean',
            'description' => 'nullable|string'
        ]);

        try {
            $mapping->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Mapeamento atualizado com sucesso',
                'mapping' => $mapping
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar mapeamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deletar mapeamento
     */
    public function deleteMapping($id)
    {
        try {
            $mapping = WebhookMapping::findOrFail($id);
            $mapping->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mapeamento deletado com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar mapeamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obter logs de webhook
     */
    public function getLogs(Request $request)
    {
        $logs = WebhookLog::when($request->source, function ($query, $source) {
                return $query->where('source', $source);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->event_type, function ($query, $eventType) {
                return $query->where('event_type', $eventType);
            })
            ->orderBy('received_at', 'desc')
            ->paginate($request->input('per_page', 50));

        return response()->json([
            'success' => true,
            'logs' => $logs
        ]);
    }

    /**
     * Reprocessar webhook
     */
    public function reprocessWebhook($id)
    {
        try {
            $webhookLog = WebhookLog::findOrFail($id);
            
            if ($webhookLog->status === 'processing') {
                return response()->json([
                    'success' => false,
                    'message' => 'Webhook já está sendo processado'
                ], 400);
            }

            $webhookLog->update([
                'status' => 'pending',
                'error_message' => null,
                'retry_count' => $webhookLog->retry_count + 1
            ]);

            $result = $this->processWebhook($webhookLog);

            return response()->json([
                'success' => true,
                'message' => 'Webhook reprocessado com sucesso',
                'result' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao reprocessar webhook: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Testar mapeamento
     */
    public function testMapping(Request $request)
    {
        $request->validate([
            'mapping_id' => 'required|exists:webhook_mappings,id',
            'test_payload' => 'required|array'
        ]);

        try {
            $mapping = WebhookMapping::findOrFail($request->mapping_id);
            $result = $this->applyMapping($mapping, $request->test_payload);

            return response()->json([
                'success' => true,
                'message' => 'Teste executado com sucesso',
                'result' => $result,
                'mapping' => $mapping
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro no teste: ' . $e->getMessage()
            ], 500);
        }
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'event_type',
        'source',
        'target_model',
        'target_field',
        'webhook_field',
        'field_type',
        'conditions',
        'transformations',
        'is_active',
        'description'
    ];

    protected $casts = [
        'conditions' => 'array',
        'transformations' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Scope para mapeamentos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para mapeamentos por fonte
     */
    public function scopeForSource($query, $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Scope para mapeamentos por tipo de evento
     */
    public function scopeForEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope para mapeamentos por modelo de destino
     */
    public function scopeForTargetModel($query, $targetModel)
    {
        return $query->where('target_model', $targetModel);
    }

    /**
     * Obter label do tipo de campo
     */
    public function getFieldTypeLabelAttribute()
    {
        $labels = [
            'string' => 'Texto',
            'integer' => 'Número Inteiro',
            'float' => 'Número Decimal',
            'boolean' => 'Verdadeiro/Falso',
            'date' => 'Data',
            'datetime' => 'Data e Hora',
            'json' => 'JSON',
            'email' => 'E-mail',
            'phone' => 'Telefone',
            'url' => 'URL'
        ];

        return $labels[$this->field_type] ?? $this->field_type;
    }

    /**
     * Obter label do modelo de destino
     */
    public function getTargetModelLabelAttribute()
    {
        $labels = [
            'Contact' => 'Contato',
            'Deal' => 'Negócio',
            'Activity' => 'Atividade',
            'WhatsAppMessage' => 'Mensagem WhatsApp',
            'User' => 'Usuário',
            'Pipeline' => 'Pipeline'
        ];

        return $labels[$this->target_model] ?? $this->target_model;
    }

    /**
     * Obter campos disponíveis para o modelo de destino
     */
    public static function getAvailableFields($targetModel)
    {
        $fields = [
            'Contact' => [
                'name' => 'Nome',
                'email' => 'E-mail',
                'phone' => 'Telefone',
                'whatsapp' => 'WhatsApp',
                'birth_date' => 'Data de Nascimento',
                'city' => 'Cidade',
                'state' => 'Estado',
                'source' => 'Origem',
                'potential_value' => 'Valor Potencial',
                'notes' => 'Observações'
            ],
            'Deal' => [
                'title' => 'Título',
                'description' => 'Descrição',
                'value' => 'Valor',
                'expected_close_date' => 'Data Prevista de Fechamento',
                'status' => 'Status',
                'lost_reason' => 'Motivo da Perda',
                'notes' => 'Observações'
            ],
            'Activity' => [
                'title' => 'Título',
                'description' => 'Descrição',
                'type' => 'Tipo',
                'duration' => 'Duração',
                'outcome' => 'Resultado',
                'activity_date' => 'Data da Atividade'
            ],
            'WhatsAppMessage' => [
                'content' => 'Conteúdo',
                'message_type' => 'Tipo de Mensagem',
                'caption' => 'Legenda',
                'media_url' => 'URL da Mídia',
                'is_read' => 'Lida',
                'is_delivered' => 'Entregue'
            ]
        ];

        return $fields[$targetModel] ?? [];
    }

    /**
     * Obter tipos de transformação disponíveis
     */
    public static function getAvailableTransformations()
    {
        return [
            'uppercase' => [
                'label' => 'Maiúsculas',
                'description' => 'Converter para maiúsculas',
                'fields' => []
            ],
            'lowercase' => [
                'label' => 'Minúsculas',
                'description' => 'Converter para minúsculas',
                'fields' => []
            ],
            'trim' => [
                'label' => 'Remover Espaços',
                'description' => 'Remover espaços no início e fim',
                'fields' => []
            ],
            'phone_format' => [
                'label' => 'Formatar Telefone',
                'description' => 'Remover caracteres não numéricos',
                'fields' => []
            ],
            'date_format' => [
                'label' => 'Formatar Data',
                'description' => 'Converter formato de data',
                'fields' => [
                    'format' => 'Formato de saída (ex: Y-m-d H:i:s)'
                ]
            ],
            'replace' => [
                'label' => 'Substituir Texto',
                'description' => 'Substituir texto específico',
                'fields' => [
                    'search' => 'Texto a procurar',
                    'replace' => 'Texto de substituição'
                ]
            ],
            'regex' => [
                'label' => 'Expressão Regular',
                'description' => 'Aplicar regex para transformação',
                'fields' => [
                    'pattern' => 'Padrão regex',
                    'replacement' => 'Substituição'
                ]
            ],
            'default' => [
                'label' => 'Valor Padrão',
                'description' => 'Usar valor padrão se vazio',
                'fields' => [
                    'value' => 'Valor padrão'
                ]
            ]
        ];
    }

    /**
     * Obter operadores de condição disponíveis
     */
    public static function getAvailableOperators()
    {
        return [
            '=' => 'Igual a',
            '!=' => 'Diferente de',
            'contains' => 'Contém',
            'not_contains' => 'Não contém',
            'exists' => 'Existe',
            'not_exists' => 'Não existe'
        ];
    }

    /**
     * Validar estrutura do mapeamento
     */
    public function validate()
    {
        $errors = [];

        // Verificar se o modelo de destino existe
        $modelClass = 'App\\Models\\' . $this->target_model;
        if (!class_exists($modelClass)) {
            $errors[] = "Modelo {$this->target_model} não encontrado";
        }

        // Verificar se o campo de destino é válido
        $availableFields = self::getAvailableFields($this->target_model);
        if (!empty($availableFields) && !isset($availableFields[$this->target_field])) {
            $errors[] = "Campo {$this->target_field} não é válido para o modelo {$this->target_model}";
        }

        // Validar condições
        if ($this->conditions) {
            foreach ($this->conditions as $condition) {
                if (!isset($condition['field']) || !isset($condition['operator'])) {
                    $errors[] = "Condição inválida: campo e operador são obrigatórios";
                }
            }
        }

        // Validar transformações
        if ($this->transformations) {
            $availableTransformations = array_keys(self::getAvailableTransformations());
            foreach ($this->transformations as $transformation) {
                if (!isset($transformation['type']) || !in_array($transformation['type'], $availableTransformations)) {
                    $errors[] = "Tipo de transformação inválido: {$transformation['type']}";
                }
            }
        }

        return $errors;
    }

    /**
     * Criar mapeamentos padrão para Evolution API
     */
    public static function createDefaultEvolutionMappings()
    {
        $defaultMappings = [
            [
                'name' => 'Mensagem WhatsApp - Conteúdo',
                'event_type' => 'message',
                'source' => 'evolution',
                'target_model' => 'WhatsAppMessage',
                'target_field' => 'content',
                'webhook_field' => 'message.conversation',
                'field_type' => 'string',
                'description' => 'Mapear conteúdo da mensagem WhatsApp'
            ],
            [
                'name' => 'Contato - Nome do Remetente',
                'event_type' => 'message',
                'source' => 'evolution',
                'target_model' => 'Contact',
                'target_field' => 'name',
                'webhook_field' => 'pushName',
                'field_type' => 'string',
                'conditions' => [
                    ['field' => 'pushName', 'operator' => 'exists']
                ],
                'description' => 'Atualizar nome do contato baseado no pushName'
            ],
            [
                'name' => 'Atividade - Nova Mensagem',
                'event_type' => 'message',
                'source' => 'evolution',
                'target_model' => 'Activity',
                'target_field' => 'description',
                'webhook_field' => 'message.conversation',
                'field_type' => 'string',
                'transformations' => [
                    ['type' => 'default', 'value' => 'Nova mensagem WhatsApp recebida']
                ],
                'description' => 'Criar atividade para nova mensagem'
            ]
        ];

        foreach ($defaultMappings as $mappingData) {
            self::firstOrCreate(
                [
                    'name' => $mappingData['name'],
                    'source' => $mappingData['source']
                ],
                $mappingData
            );
        }
    }
}


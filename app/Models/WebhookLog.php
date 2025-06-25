<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WebhookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'event_type',
        'webhook_id',
        'payload',
        'headers',
        'status',
        'error_message',
        'processed_data',
        'applied_mappings',
        'received_at',
        'processed_at',
        'retry_count',
        'next_retry_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
        'processed_data' => 'array',
        'applied_mappings' => 'array',
        'received_at' => 'datetime',
        'processed_at' => 'datetime',
        'next_retry_at' => 'datetime'
    ];

    /**
     * Scope para logs por status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para logs por fonte
     */
    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Scope para logs por tipo de evento
     */
    public function scopeByEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope para logs recentes
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('received_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope para logs com erro
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope para logs processados com sucesso
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope para logs pendentes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para logs que podem ser reprocessados
     */
    public function scopeRetryable($query)
    {
        return $query->where('status', 'failed')
                    ->where('retry_count', '<', 3)
                    ->where(function($q) {
                        $q->whereNull('next_retry_at')
                          ->orWhere('next_retry_at', '<=', now());
                    });
    }

    /**
     * Verificar se pode ser reprocessado
     */
    public function canRetry()
    {
        return $this->status === 'failed' 
            && $this->retry_count < 3 
            && (is_null($this->next_retry_at) || $this->next_retry_at <= now());
    }

    /**
     * Verificar se está sendo processado
     */
    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    /**
     * Verificar se foi processado com sucesso
     */
    public function isSuccessful()
    {
        return $this->status === 'success';
    }

    /**
     * Verificar se falhou
     */
    public function hasFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Obter tempo de processamento
     */
    public function getProcessingTimeAttribute()
    {
        if (!$this->processed_at) {
            return null;
        }

        return $this->received_at->diffInMilliseconds($this->processed_at);
    }

    /**
     * Obter tempo de processamento formatado
     */
    public function getFormattedProcessingTimeAttribute()
    {
        $time = $this->processing_time;
        
        if (!$time) {
            return '-';
        }

        if ($time < 1000) {
            return $time . 'ms';
        }

        return round($time / 1000, 2) . 's';
    }

    /**
     * Obter data de recebimento formatada
     */
    public function getFormattedReceivedAtAttribute()
    {
        return $this->received_at->format('d/m/Y H:i:s');
    }

    /**
     * Obter data de processamento formatada
     */
    public function getFormattedProcessedAtAttribute()
    {
        return $this->processed_at ? $this->processed_at->format('d/m/Y H:i:s') : '-';
    }

    /**
     * Obter cor do status
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'processing' => 'info',
            'success' => 'success',
            'failed' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Obter label do status
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pendente',
            'processing' => 'Processando',
            'success' => 'Sucesso',
            'failed' => 'Falhou'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Obter ícone do status
     */
    public function getStatusIconAttribute()
    {
        $icons = [
            'pending' => 'fas fa-clock',
            'processing' => 'fas fa-spinner fa-spin',
            'success' => 'fas fa-check-circle',
            'failed' => 'fas fa-times-circle'
        ];

        return $icons[$this->status] ?? 'fas fa-question-circle';
    }

    /**
     * Obter resumo do payload
     */
    public function getPayloadSummaryAttribute()
    {
        if (!$this->payload) {
            return 'Vazio';
        }

        $summary = [];
        
        if (isset($this->payload['messageType'])) {
            $summary[] = 'Tipo: ' . $this->payload['messageType'];
        }
        
        if (isset($this->payload['key']['remoteJid'])) {
            $summary[] = 'De: ' . $this->payload['key']['remoteJid'];
        }
        
        if (isset($this->payload['message']['conversation'])) {
            $content = substr($this->payload['message']['conversation'], 0, 50);
            $summary[] = 'Conteúdo: ' . $content . (strlen($this->payload['message']['conversation']) > 50 ? '...' : '');
        }

        return implode(' | ', $summary) ?: 'Dados disponíveis';
    }

    /**
     * Obter contagem de mapeamentos aplicados
     */
    public function getAppliedMappingsCountAttribute()
    {
        return is_array($this->applied_mappings) ? count($this->applied_mappings) : 0;
    }

    /**
     * Programar próxima tentativa
     */
    public function scheduleRetry($minutes = null)
    {
        if (!$minutes) {
            // Backoff exponencial: 5min, 15min, 45min
            $delays = [5, 15, 45];
            $minutes = $delays[min($this->retry_count, count($delays) - 1)];
        }

        $this->update([
            'next_retry_at' => now()->addMinutes($minutes)
        ]);
    }

    /**
     * Marcar como processando
     */
    public function markAsProcessing()
    {
        $this->update([
            'status' => 'processing',
            'error_message' => null
        ]);
    }

    /**
     * Marcar como sucesso
     */
    public function markAsSuccess($processedData = null, $appliedMappings = null)
    {
        $this->update([
            'status' => 'success',
            'processed_at' => now(),
            'processed_data' => $processedData,
            'applied_mappings' => $appliedMappings,
            'error_message' => null
        ]);
    }

    /**
     * Marcar como falha
     */
    public function markAsFailed($errorMessage)
    {
        $this->update([
            'status' => 'failed',
            'processed_at' => now(),
            'error_message' => $errorMessage,
            'retry_count' => $this->retry_count + 1
        ]);

        // Programar próxima tentativa se ainda pode tentar
        if ($this->retry_count < 3) {
            $this->scheduleRetry();
        }
    }

    /**
     * Obter estatísticas de logs
     */
    public static function getStats($period = 24)
    {
        $startDate = now()->subHours($period);

        return [
            'total' => self::where('received_at', '>=', $startDate)->count(),
            'success' => self::where('received_at', '>=', $startDate)->where('status', 'success')->count(),
            'failed' => self::where('received_at', '>=', $startDate)->where('status', 'failed')->count(),
            'pending' => self::where('received_at', '>=', $startDate)->where('status', 'pending')->count(),
            'processing' => self::where('received_at', '>=', $startDate)->where('status', 'processing')->count(),
            'avg_processing_time' => self::where('received_at', '>=', $startDate)
                ->where('status', 'success')
                ->whereNotNull('processed_at')
                ->get()
                ->avg(function($log) {
                    return $log->processing_time;
                }),
            'by_source' => self::where('received_at', '>=', $startDate)
                ->selectRaw('source, COUNT(*) as count')
                ->groupBy('source')
                ->pluck('count', 'source'),
            'by_event_type' => self::where('received_at', '>=', $startDate)
                ->selectRaw('event_type, COUNT(*) as count')
                ->groupBy('event_type')
                ->pluck('count', 'event_type')
        ];
    }

    /**
     * Limpar logs antigos
     */
    public static function cleanup($days = 30)
    {
        $cutoffDate = now()->subDays($days);
        
        return self::where('received_at', '<', $cutoffDate)
                  ->where('status', '!=', 'failed') // Manter logs com falha para análise
                  ->delete();
    }
}


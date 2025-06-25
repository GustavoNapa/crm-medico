<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WhatsAppMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'instance_name',
        'message_id',
        'from',
        'to',
        'contact_id',
        'direction',
        'message_type',
        'content',
        'media_url',
        'media_type',
        'caption',
        'metadata',
        'message_timestamp',
        'is_read',
        'is_delivered',
        'is_sent',
        'error_message'
    ];

    protected $casts = [
        'metadata' => 'array',
        'message_timestamp' => 'datetime',
        'is_read' => 'boolean',
        'is_delivered' => 'boolean',
        'is_sent' => 'boolean'
    ];

    /**
     * Relacionamento com contato
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Scope para mensagens de entrada
     */
    public function scopeInbound($query)
    {
        return $query->where('direction', 'inbound');
    }

    /**
     * Scope para mensagens de saída
     */
    public function scopeOutbound($query)
    {
        return $query->where('direction', 'outbound');
    }

    /**
     * Scope para mensagens não lidas
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope para mensagens de um contato
     */
    public function scopeForContact($query, $contactId)
    {
        return $query->where('contact_id', $contactId);
    }

    /**
     * Scope para mensagens de texto
     */
    public function scopeTextMessages($query)
    {
        return $query->where('message_type', 'text');
    }

    /**
     * Scope para mensagens com mídia
     */
    public function scopeMediaMessages($query)
    {
        return $query->whereIn('message_type', ['image', 'audio', 'video', 'document']);
    }

    /**
     * Scope ordenado por timestamp
     */
    public function scopeOrdered($query, $direction = 'asc')
    {
        return $query->orderBy('message_timestamp', $direction);
    }

    /**
     * Verificar se é mensagem de entrada
     */
    public function isInbound()
    {
        return $this->direction === 'inbound';
    }

    /**
     * Verificar se é mensagem de saída
     */
    public function isOutbound()
    {
        return $this->direction === 'outbound';
    }

    /**
     * Verificar se tem mídia
     */
    public function hasMedia()
    {
        return in_array($this->message_type, ['image', 'audio', 'video', 'document']);
    }

    /**
     * Obter timestamp formatado
     */
    public function getFormattedTimestampAttribute()
    {
        return $this->message_timestamp->format('d/m/Y H:i');
    }

    /**
     * Obter timestamp relativo
     */
    public function getRelativeTimestampAttribute()
    {
        return $this->message_timestamp->diffForHumans();
    }

    /**
     * Obter ícone do tipo de mensagem
     */
    public function getTypeIconAttribute()
    {
        $icons = [
            'text' => 'fas fa-comment',
            'image' => 'fas fa-image',
            'audio' => 'fas fa-microphone',
            'video' => 'fas fa-video',
            'document' => 'fas fa-file',
            'location' => 'fas fa-map-marker-alt',
            'contact' => 'fas fa-address-card',
            'sticker' => 'fas fa-smile'
        ];

        return $icons[$this->message_type] ?? 'fas fa-comment';
    }

    /**
     * Obter número formatado
     */
    public function getFormattedFromAttribute()
    {
        $number = preg_replace('/\D/', '', $this->from);
        
        if (strlen($number) >= 13) {
            // Remove @s.whatsapp.net e código do país
            $number = substr($number, 2);
        }
        
        if (strlen($number) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $number);
        } elseif (strlen($number) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $number);
        }
        
        return $this->from;
    }

    /**
     * Marcar como lida
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
        return $this;
    }

    /**
     * Marcar como entregue
     */
    public function markAsDelivered()
    {
        $this->update(['is_delivered' => true]);
        return $this;
    }

    /**
     * Marcar como enviada
     */
    public function markAsSent()
    {
        $this->update(['is_sent' => true]);
        return $this;
    }

    /**
     * Criar mensagem de entrada
     */
    public static function createInbound($data)
    {
        // Tentar encontrar contato pelo número
        $phoneNumber = preg_replace('/\D/', '', $data['from']);
        $contact = Contact::where('whatsapp', 'like', '%' . substr($phoneNumber, -9) . '%')
                         ->orWhere('phone', 'like', '%' . substr($phoneNumber, -9) . '%')
                         ->first();

        return self::create([
            'instance_name' => $data['instance'] ?? 'default',
            'message_id' => $data['key']['id'],
            'from' => $data['key']['remoteJid'],
            'contact_id' => $contact?->id,
            'direction' => 'inbound',
            'message_type' => $data['messageType'] ?? 'text',
            'content' => $data['message']['conversation'] ?? $data['message']['extendedTextMessage']['text'] ?? '',
            'metadata' => $data,
            'message_timestamp' => Carbon::createFromTimestamp($data['messageTimestamp'])
        ]);
    }

    /**
     * Criar mensagem de saída
     */
    public static function createOutbound($instanceName, $to, $content, $contactId = null, $messageType = 'text')
    {
        return self::create([
            'instance_name' => $instanceName,
            'message_id' => uniqid('out_'),
            'from' => $instanceName,
            'to' => $to,
            'contact_id' => $contactId,
            'direction' => 'outbound',
            'message_type' => $messageType,
            'content' => $content,
            'message_timestamp' => now(),
            'is_sent' => true
        ]);
    }
}


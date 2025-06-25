<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'description',
        'subject_type',
        'subject_id',
        'user_id',
        'contact_id',
        'deal_id',
        'metadata',
        'activity_date',
        'duration',
        'status',
        'outcome'
    ];

    protected $casts = [
        'metadata' => 'array',
        'activity_date' => 'datetime'
    ];

    /**
     * Relacionamento polimórfico com o subject
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com contato
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Relacionamento com deal
     */
    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    /**
     * Scope para atividades de um contato
     */
    public function scopeForContact($query, $contactId)
    {
        return $query->where('contact_id', $contactId);
    }

    /**
     * Scope para atividades de um deal
     */
    public function scopeForDeal($query, $dealId)
    {
        return $query->where('deal_id', $dealId);
    }

    /**
     * Scope para atividades por tipo
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope para atividades recentes
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('activity_date', '>=', now()->subDays($days));
    }

    /**
     * Scope ordenado por data
     */
    public function scopeOrdered($query, $direction = 'desc')
    {
        return $query->orderBy('activity_date', $direction);
    }

    /**
     * Obter ícone do tipo de atividade
     */
    public function getIconAttribute()
    {
        $icons = [
            'call' => 'fas fa-phone',
            'email' => 'fas fa-envelope',
            'whatsapp' => 'fab fa-whatsapp',
            'meeting' => 'fas fa-calendar',
            'note' => 'fas fa-sticky-note',
            'deal_created' => 'fas fa-plus-circle',
            'deal_moved' => 'fas fa-arrows-alt',
            'deal_won' => 'fas fa-trophy',
            'deal_lost' => 'fas fa-times-circle',
            'contact_created' => 'fas fa-user-plus',
            'contact_updated' => 'fas fa-user-edit',
            'task' => 'fas fa-tasks',
            'appointment' => 'fas fa-calendar-check'
        ];

        return $icons[$this->type] ?? 'fas fa-circle';
    }

    /**
     * Obter cor do tipo de atividade
     */
    public function getColorAttribute()
    {
        $colors = [
            'call' => 'primary',
            'email' => 'info',
            'whatsapp' => 'success',
            'meeting' => 'warning',
            'note' => 'secondary',
            'deal_created' => 'success',
            'deal_moved' => 'info',
            'deal_won' => 'success',
            'deal_lost' => 'danger',
            'contact_created' => 'primary',
            'contact_updated' => 'info',
            'task' => 'warning',
            'appointment' => 'primary'
        ];

        return $colors[$this->type] ?? 'secondary';
    }

    /**
     * Obter label do tipo de atividade
     */
    public function getTypeLabelAttribute()
    {
        $labels = [
            'call' => 'Ligação',
            'email' => 'E-mail',
            'whatsapp' => 'WhatsApp',
            'meeting' => 'Reunião',
            'note' => 'Anotação',
            'deal_created' => 'Negócio Criado',
            'deal_moved' => 'Negócio Movido',
            'deal_won' => 'Negócio Ganho',
            'deal_lost' => 'Negócio Perdido',
            'contact_created' => 'Contato Criado',
            'contact_updated' => 'Contato Atualizado',
            'task' => 'Tarefa',
            'appointment' => 'Consulta'
        ];

        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Obter data formatada
     */
    public function getFormattedDateAttribute()
    {
        return $this->activity_date->format('d/m/Y H:i');
    }

    /**
     * Obter data relativa
     */
    public function getRelativeDateAttribute()
    {
        return $this->activity_date->diffForHumans();
    }

    /**
     * Obter duração formatada
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) {
            return null;
        }

        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'min';
        }

        return $minutes . 'min';
    }

    /**
     * Criar atividade de ligação
     */
    public static function createCall($contactId, $userId, $duration = null, $outcome = null)
    {
        return self::create([
            'type' => 'call',
            'title' => 'Ligação realizada',
            'contact_id' => $contactId,
            'user_id' => $userId,
            'activity_date' => now(),
            'duration' => $duration,
            'outcome' => $outcome
        ]);
    }

    /**
     * Criar atividade de WhatsApp
     */
    public static function createWhatsApp($contactId, $userId, $message = null)
    {
        return self::create([
            'type' => 'whatsapp',
            'title' => 'Mensagem WhatsApp',
            'description' => $message,
            'contact_id' => $contactId,
            'user_id' => $userId,
            'activity_date' => now()
        ]);
    }

    /**
     * Criar atividade de deal
     */
    public static function createDealActivity($type, $dealId, $userId, $description = null)
    {
        $deal = Deal::find($dealId);
        
        return self::create([
            'type' => $type,
            'title' => 'Atividade do negócio: ' . $deal->title,
            'description' => $description,
            'subject_type' => Deal::class,
            'subject_id' => $dealId,
            'contact_id' => $deal->contact_id,
            'deal_id' => $dealId,
            'user_id' => $userId,
            'activity_date' => now()
        ]);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'contact_id',
        'pipeline_id',
        'user_id',
        'value',
        'expected_close_date',
        'actual_close_date',
        'status',
        'lost_reason',
        'position',
        'custom_fields',
        'last_activity'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'expected_close_date' => 'date',
        'actual_close_date' => 'date',
        'custom_fields' => 'array',
        'last_activity' => 'datetime'
    ];

    /**
     * Relacionamento com contato
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Relacionamento com pipeline
     */
    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }

    /**
     * Relacionamento com usuário responsável
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para deals em aberto
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope para deals ganhos
     */
    public function scopeWon($query)
    {
        return $query->where('status', 'won');
    }

    /**
     * Scope para deals perdidos
     */
    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }

    /**
     * Scope ordenado por posição
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    /**
     * Verificar se está atrasado
     */
    public function getIsOverdueAttribute()
    {
        if (!$this->expected_close_date || $this->status !== 'open') {
            return false;
        }
        
        return Carbon::parse($this->expected_close_date)->isPast();
    }

    /**
     * Dias até o fechamento esperado
     */
    public function getDaysToCloseAttribute()
    {
        if (!$this->expected_close_date || $this->status !== 'open') {
            return null;
        }
        
        return Carbon::now()->diffInDays(Carbon::parse($this->expected_close_date), false);
    }

    /**
     * Valor formatado
     */
    public function getFormattedValueAttribute()
    {
        if (!$this->value) {
            return 'R$ 0,00';
        }
        
        return 'R$ ' . number_format($this->value, 2, ',', '.');
    }

    /**
     * Status formatado
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'open' => 'Em Andamento',
            'won' => 'Ganho',
            'lost' => 'Perdido'
        ];
        
        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Cor do status
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'open' => 'primary',
            'won' => 'success',
            'lost' => 'danger'
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Última atividade formatada
     */
    public function getLastActivityFormattedAttribute()
    {
        if (!$this->last_activity) {
            return 'Nunca';
        }
        
        return $this->last_activity->diffForHumans();
    }

    /**
     * Mover para outro pipeline
     */
    public function moveToPipeline($pipelineId, $position = null)
    {
        $this->pipeline_id = $pipelineId;
        
        if ($position !== null) {
            $this->position = $position;
        } else {
            // Colocar no final do pipeline
            $maxPosition = self::where('pipeline_id', $pipelineId)->max('position');
            $this->position = $maxPosition + 1;
        }
        
        $this->last_activity = now();
        $this->save();
        
        return $this;
    }

    /**
     * Marcar como ganho
     */
    public function markAsWon()
    {
        $this->status = 'won';
        $this->actual_close_date = now();
        $this->last_activity = now();
        $this->save();
        
        return $this;
    }

    /**
     * Marcar como perdido
     */
    public function markAsLost($reason = null)
    {
        $this->status = 'lost';
        $this->lost_reason = $reason;
        $this->actual_close_date = now();
        $this->last_activity = now();
        $this->save();
        
        return $this;
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'whatsapp',
        'birth_date',
        'address',
        'city',
        'state',
        'zip_code',
        'cpf',
        'rg',
        'gender',
        'medical_history',
        'allergies',
        'medications',
        'notes',
        'source',
        'potential_value',
        'last_contact',
        'is_active'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'last_contact' => 'datetime',
        'potential_value' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Relacionamento com deals
     */
    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    /**
     * Deal ativo (último deal em aberto)
     */
    public function activeDeal()
    {
        return $this->hasOne(Deal::class)->where('status', 'open')->latest();
    }

    /**
     * Scope para contatos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Calcular idade
     */
    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }
        
        return Carbon::parse($this->birth_date)->age;
    }

    /**
     * Obter iniciais do nome
     */
    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            if (!empty($name)) {
                $initials .= strtoupper($name[0]);
            }
        }
        
        return substr($initials, 0, 2);
    }

    /**
     * Formatar telefone
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) {
            return null;
        }
        
        $phone = preg_replace('/\D/', '', $this->phone);
        
        if (strlen($phone) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $phone);
        } elseif (strlen($phone) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $phone);
        }
        
        return $this->phone;
    }

    /**
     * Formatar WhatsApp
     */
    public function getFormattedWhatsappAttribute()
    {
        if (!$this->whatsapp) {
            return null;
        }
        
        $whatsapp = preg_replace('/\D/', '', $this->whatsapp);
        
        if (strlen($whatsapp) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $whatsapp);
        } elseif (strlen($whatsapp) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $whatsapp);
        }
        
        return $this->whatsapp;
    }

    /**
     * Verificar se tem WhatsApp
     */
    public function hasWhatsApp()
    {
        return !empty($this->whatsapp);
    }

    /**
     * Obter último contato formatado
     */
    public function getLastContactFormattedAttribute()
    {
        if (!$this->last_contact) {
            return 'Nunca';
        }
        
        return $this->last_contact->diffForHumans();
    }
}


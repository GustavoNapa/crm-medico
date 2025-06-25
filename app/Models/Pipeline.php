<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pipeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'position',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Relacionamento com deals
     */
    public function deals()
    {
        return $this->hasMany(Deal::class)->orderBy('position');
    }

    /**
     * Scope para pipelines ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordenado por posição
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    /**
     * Calcular valor total dos deals no pipeline
     */
    public function getTotalValueAttribute()
    {
        return $this->deals()->where('status', 'open')->sum('value');
    }

    /**
     * Contar deals no pipeline
     */
    public function getDealsCountAttribute()
    {
        return $this->deals()->where('status', 'open')->count();
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'offers';

    protected $fillable = [
        'company_id',
        'puesto',
        'tipo_contrato',
        'jornada_pct',
        'ubicacion',
        'requisitos',
        'estado',
        'fecha',
    ];

    // Desactivar los timestamps automáticos
    public $timestamps = false;

    protected $casts = [
        'fecha' => 'date',
        'jornada_pct' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function getEstadoBadgeClassesAttribute()
    {
        return match ($this->estado) {
            'abierta' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200',
            'en_proceso' => 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-200',
            'cerrada' => 'bg-gray-200 text-gray-700 dark:bg-slate-700/40 dark:text-slate-200',
            'pausada' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-200',
            default => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200',
        };
    }

    public function getDisplayTitleAttribute()
    {
        return $this->puesto ?: ('Oferta #'.$this->id);
    }
}

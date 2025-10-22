<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';

    public $timestamps = false;
    
    protected $fillable = [
        'participant_id',
        'offer_id',
        'estado',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relaciones
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    // Helpers UI
    public function getEstadoBadgeClassesAttribute()
    {
        return match ($this->estado) {
            'pendiente'   => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-200',
            'en_proceso'  => 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-200',
            'aceptada'    => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200',
            'rechazada'   => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-200',
            default       => 'bg-gray-100 text-gray-700 dark:bg-slate-700/40 dark:text-slate-200',
        };
    }
}

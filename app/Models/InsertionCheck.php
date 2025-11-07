<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsertionCheck extends Model
{
    protected $table = 'insertion_checks';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'participant_id',
        'periodo_valido',
        'dias_validos',
        'fuente',
        'parcialidad',
        'valido_90_dias',
        'observaciones',
        'observaciones2',
        'fecha',
    ];

    protected $casts = [
        'periodo_valido' => 'boolean',
        'valido_90_dias' => 'boolean',
        'dias_validos'   => 'integer',
        'parcialidad'    => 'integer',
        'fecha'          => 'datetime',
    ];

    public function participant()
    {
        return $this->belongsTo(\App\Models\Participant::class, 'participant_id');
    }
}

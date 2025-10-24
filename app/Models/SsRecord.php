<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SSRecord extends Model
{
    protected $table = 'ss_records';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'participant_id',
        'regimen',
        'dias_alta',
        'jornadas_reales',
        'coef_aplicado',
        'dias_equivalentes',
        'observaciones',
    ];

    protected $casts = [
        'participant_id'   => 'integer',
        'dias_alta'        => 'integer',
        'jornadas_reales'  => 'integer',
        'coef_aplicado'    => 'decimal:4',
        'dias_equivalentes'=> 'integer',
    ];

    public function participant()
    {
        return $this->belongsTo(\App\Models\Participant::class, 'participant_id');
    }
}

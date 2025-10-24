<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = 'participants';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'dni_nie',
        'nombre',
        'telefono',
        'email',
        'fecha_alta_prog',
        'provincia',
        'tutor_id',
        'estado',
        'consent_rgpd',
        'notas',
        'id_cv',
        // ❌ quitado: 'id_notas_trabajador',
    ];

    protected $casts = [
        'fecha_alta_prog' => 'date',
        'consent_rgpd'    => 'boolean',
    ];

    // --- Relaciones ---
    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function cv()
    {
        return $this->belongsTo(CV::class, 'id_cv');
    }

    // Todas las notas del participante (notas_trabajador.id_participante -> participants.id)
    public function notasTrabajador()
    {
        return $this->hasMany(\App\Models\NotaTrabajador::class, 'id_participante', 'id');
    }

    // Compatibilidad con vistas: "última" nota como antes (método se sigue llamando igual)
    public function notaTrabajador()
    {
        // Si tu versión de Laravel soporta latestOfMany:
        return $this->hasOne(\App\Models\NotaTrabajador::class, 'id_participante', 'id')
                    ->latestOfMany('fecha_hora');

        // Si no, usa esto:
        // return $this->hasOne(\App\Models\NotaTrabajador::class, 'id_participante', 'id')->latest('fecha_hora');
    }

    public function ssRecords()
    {
        return $this->hasMany(\App\Models\SSRecord::class, 'participant_id', 'id');
    }
}

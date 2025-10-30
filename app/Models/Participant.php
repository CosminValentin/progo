<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participant extends Model
{
    protected $table = 'participants';

    protected $fillable = [
    'dni_nie','nombre','telefono','email','fecha_alta_prog','provincia',
    'tutor_id','estado','consent_rgpd','notas','observaciones2','id_cv'
    ];

    public $timestamps = false; // si tu tabla no usa timestamps (según tu dump)

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function ultimaNota()
    {
        return $this->hasOne(NotaTrabajador::class, 'id_participante')->latestOfMany('fecha_hora');
    }

    // Resto de relaciones usadas en el timeline
    public function contracts()       { return $this->hasMany(Contract::class, 'participant_id'); }
    public function agreements()      { return $this->hasMany(Agreement::class, 'participant_id'); } // si tu agreements no enlaza con participant, ignóralo
    public function applications()    { return $this->hasMany(Application::class, 'participant_id'); }
    public function ssRecords()       { return $this->hasMany(SSRecord::class, 'participant_id'); }
    public function insertionChecks() { return $this->hasMany(InsertionCheck::class, 'participant_id'); }

    public function cvsDocuments() {
    return $this->morphMany(\App\Models\Document::class, 'owner', 'owner_type', 'owner_id')
        ->where('owner_type', 'participants')
        ->where('tipo', 'cv');
}

    // Todos los documents del participante
    public function documents() {
        return $this->morphMany(\App\Models\Document::class, 'owner', 'owner_type', 'owner_id')
            ->where('owner_type', 'participants');
    }

    // CV en tabla cv (columna participants.id_cv -> cv.id)
    public function cvFile() {
        return $this->belongsTo(\App\Models\Cv::class, 'id_cv', 'id');
    }


    public function notasTrabajador(): HasMany
    {
        return $this->hasMany(\App\Models\NotaTrabajador::class, 'id_participante', 'id')
                    ->with('usuario')
                    ->orderByDesc('fecha_hora');
    }

    
}

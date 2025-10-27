<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    /**
     * Documentos polimórficos (Document.owner_type = 'participants')


    
     * CVs almacenados en documents con tipo='cv' (si los usas)
     */

    /**
     * Relación al CV de la tabla cv (FK participants.id_cv -> cv.id)
     */


    /**
     * Todas las notas del trabajador (FK real id_participante)
     */


    /**
     * Última nota del trabajador por fecha_hora (sin romper por PK distinta)
     * latestOfMany usará la PK de NotaTrabajador (id_nota) porque la hemos definido.
     */
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

    // Notas trabajador (tabla notas_trabajador)
    public function notasTrabajador() {
        return $this->hasMany(\App\Models\NotaTrabajador::class, 'id_participante', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaTrabajador extends Model
{
    protected $table = 'notas_trabajador';
    protected $primaryKey = 'id_nota';
    public $timestamps = false; // no hay created_at/updated_at
    protected $fillable = ['texto','fecha_hora','estado','id_usuario_trabajador','id_participante'];

    public function usuario() {
        return $this->belongsTo(\App\Models\User::class, 'id_usuario_trabajador');
    }

    public function participant() {
        return $this->belongsTo(\App\Models\Participant::class, 'id_participante', 'id');
    }
}

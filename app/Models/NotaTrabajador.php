<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotaTrabajador extends Model
{
    use HasFactory;

    protected $table = 'notas_trabajador';
    protected $primaryKey = 'id_nota';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    // ✅ Desbloquea asignación masiva para evitar sorpresas
    protected $guarded = [];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_usuario_trabajador');
    }

    public function participant()
    {
        // ✅ Asegúrate de que la FK coincida con la columna de tu tabla
        return $this->belongsTo(\App\Models\Participant::class, 'id_participante');
    }
}

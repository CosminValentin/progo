<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotaTrabajador extends Model
{
    protected $table = 'notas_trabajador';
    protected $primaryKey = 'id_nota';
    public $timestamps = false;

    protected $fillable = [
        'texto',
        'fecha_hora',
        'estado',
        'id_usuario_trabajador',
        'id_participante',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_trabajador');
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class, 'id_participante', 'id');
    }
}

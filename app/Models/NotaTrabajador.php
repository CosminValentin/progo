<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaTrabajador extends Model
{
    use HasFactory;

    // Nombre real de la tabla
    protected $table = 'notas_trabajador';

    // Clave primaria
    protected $primaryKey = 'id_nota';

    // Si no tienes timestamps (created_at, updated_at) en la tabla:
    public $timestamps = false;

    // Campos editables
    protected $fillable = [
        'descripcion',
        'observaciones',
        'fecha',
    ];

    // Relación inversa con participantes
    public function participants()
    {
        return $this->hasMany(Participant::class, 'id_notas_trabajador', 'id_nota');
    }
}

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
        'id_notas_trabajador',
    ];

    protected $casts = [
        'fecha_alta_prog' => 'date',
        'consent_rgpd' => 'boolean',
    ];

    // Relaciones opcionales
    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function cv()
    {
        return $this->belongsTo(CV::class, 'id_cv');
    }

    public function notaTrabajador()
    {
        return $this->belongsTo(NotaTrabajador::class, 'id_notas_trabajador', 'id_nota');
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = 'participants';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'dni_nie','nombre','telefono','email','fecha_alta_prog',
        'provincia','tutor_id','estado','consent_rgpd','notas',
    ];

    protected $casts = [
        'fecha_alta_prog' => 'date',
        'consent_rgpd' => 'boolean',
    ];
}

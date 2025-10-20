<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offers';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'company_id','puesto','tipo_contrato','jornada_pct',
        'ubicacion','requisitos','estado','fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];
}

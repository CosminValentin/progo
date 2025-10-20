<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'contracts';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'participant_id','company_id','offer_id',
        'fecha_inicio','fecha_fin_prevista','tipo_contrato',
        'jornada_pct','registrado_contrata','contrata_doc_id','alta_ss_doc_id',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin_prevista' => 'date',
        'registrado_contrata' => 'boolean',
    ];
}

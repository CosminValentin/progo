<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'contracts';
    protected $primaryKey = 'id';
    public $timestamps = false; // ← importante (tu tabla no tiene timestamps)

    protected $fillable = [
        'participant_id',
        'company_id',
        'offer_id',
        'fecha_inicio',
        'fecha_fin_prevista',
        'tipo_contrato',
        'jornada_pct',
        'registrado_contrata',
        'contrata_doc_id',
        'alta_ss_doc_id',
    ];

    protected $casts = [
        'fecha_inicio'       => 'date',
        'fecha_fin_prevista' => 'date',
        'registrado_contrata'=> 'boolean',
    ];

    // Relaciones (opcional)
    public function participant(){ return $this->belongsTo(\App\Models\Participant::class, 'participant_id'); }
    public function company(){ return $this->belongsTo(\App\Models\Company::class, 'company_id'); }
    public function offer(){ return $this->belongsTo(\App\Models\Offer::class, 'offer_id'); }
    public function contrataDoc(){ return $this->belongsTo(\App\Models\Document::class, 'contrata_doc_id'); }
    public function altaSSDoc(){ return $this->belongsTo(\App\Models\Document::class, 'alta_ss_doc_id'); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agreement extends Model
{
    protected $table = 'agreements';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'fecha_firma',
        'firmado_agencia',
        'firmado_empresa',
        'validez_desde',
        'validez_hasta',
        'pdf_doc_id',
    ];

    protected $casts = [
        'fecha_firma'     => 'date',
        'validez_desde'   => 'date',
        'validez_hasta'   => 'date',
        'firmado_agencia' => 'boolean',
        'firmado_empresa' => 'boolean',
    ];

    // Para que $a->vigente esté disponible en arrays/json si lo necesitas
    protected $appends = ['vigente'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function pdf(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'pdf_doc_id', 'id');
    }

    // Accessor: $agreement->vigente
    public function getVigenteAttribute(): bool
    {
        $hoy = now()->startOfDay();
        $desdeOk = is_null($this->validez_desde) || $this->validez_desde->lte($hoy);
        $hastaOk = is_null($this->validez_hasta) || $this->validez_hasta->gte($hoy);
        return $desdeOk && $hastaOk;
    }
}

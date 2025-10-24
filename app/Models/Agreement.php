<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    protected $table = 'agreements';
    protected $primaryKey = 'id';
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

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id');
    }

    public function pdf()
    {
        return $this->belongsTo(\App\Models\Document::class, 'pdf_doc_id');
    }

    // Convenio vigente hoy
    public function getVigenteAttribute(): bool
    {
        $hoy = now()->startOfDay();
        $desde = $this->validez_desde ? $this->validez_desde->startOfDay() : null;
        $hasta = $this->validez_hasta ? $this->validez_hasta->endOfDay() : null;

        $okDesde = !$desde || $hoy->greaterThanOrEqualTo($desde);
        $okHasta = !$hasta || $hoy->lessThanOrEqualTo($hasta);
        return $okDesde && $okHasta;
    }
}

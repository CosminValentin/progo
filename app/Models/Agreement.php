<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    protected $table = 'agreements';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'company_id','fecha_firma','firmado_agencia','firmado_empresa',
        'validez_desde','validez_hasta','pdf_doc_id',
    ];

    protected $casts = [
        'fecha_firma' => 'date',
        'validez_desde' => 'date',
        'validez_hasta' => 'date',
        'firmado_agencia' => 'boolean',
        'firmado_empresa' => 'boolean',
    ];
}

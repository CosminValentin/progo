<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfContrato extends Model
{
    protected $table = 'pdf_contrato';
    public $timestamps = false;

    protected $fillable = ['contract_id','filename','mime','data'];

    // En MySQL será LONGBLOB; en Postgres bytea. No hace falta cast especial.
    public function contract() { return $this->belongsTo(Contract::class); }
}

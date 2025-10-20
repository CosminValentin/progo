<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'owner_type','owner_id','tipo','nombre_archivo',
        'hash','uploader_id','fecha','protegido',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'protegido' => 'boolean',
    ];
}

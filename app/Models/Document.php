<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    public $timestamps = false;

    protected $fillable = [
        'owner_type',
        'owner_id',
        'tipo',
        'nombre_archivo',
        'hash',
        'uploader_id',
        'fecha',
        'protegido',
    ];

    protected $casts = [
        'fecha'     => 'datetime',
        'protegido' => 'boolean',
    ];

    // Polimórfica: owner_type (clave corta) + owner_id
    public function owner()
    {
        return $this->morphTo(__FUNCTION__, 'owner_type', 'owner_id');
    }

    // Usuario que sube
    public function uploader()
    {
        return $this->belongsTo(\App\Models\User::class, 'uploader_id');
    }

    // Ruta interna de almacenamiento
    public function storagePath(): string
    {
        return 'documents/'.$this->hash;
    }
}

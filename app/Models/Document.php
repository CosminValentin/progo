<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    public $timestamps = false; // la tabla usa 'fecha' en vez de created_at/updated_at

    protected $fillable = [
        'owner_type',     // 'participants' | 'companies' | 'offers' | 'users'
        'owner_id',
        'tipo',           // 'cv' | 'contrata' | etc
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

    // Polimórfica con morphMap (AppServiceProvider->Relation::enforceMorphMap([...]))
    public function owner()
    {
        return $this->morphTo(null, 'owner_type', 'owner_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    /* Scopes útiles */
    public function scopeCv($q)
    {
        return $q->where('tipo','cv')->where('owner_type','participants');
    }
}

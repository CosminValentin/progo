<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    public $timestamps = false;

    protected $fillable = [
        'cif_nif',
        'nombre',
        'ambito',
        'actividad',
        'contacto_nombre',
        'contacto_email',
        'contacto_tel',
    ];
}

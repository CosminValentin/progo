<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'cif_nif','nombre','ambito','actividad',
        'contacto_nombre','contacto_email','contacto_tel',
    ];
}

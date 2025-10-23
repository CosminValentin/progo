<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CV extends Model
{
    use HasFactory;

    protected $table = 'cv'; 

    protected $primaryKey = 'id'; 

    protected $fillable = [
        'titulo',
        'archivo',
        'descripcion',
        'created_at',
        'updated_at',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class, 'id_cv');
    }
}

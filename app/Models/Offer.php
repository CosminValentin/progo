<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'offers';

    protected $guarded = [];

    // Relaciones
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // Título para mostrar en selects / vistas
    public function getDisplayTitleAttribute()
    {
        return $this->titulo ?? $this->nombre ?? ('Oferta #'.$this->id);
    }
}

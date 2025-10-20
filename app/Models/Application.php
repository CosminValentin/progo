<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'applications';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'participant_id','offer_id','estado','fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];
}

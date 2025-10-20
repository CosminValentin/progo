<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SsRecord extends Model
{
    protected $table = 'ss_records';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'participant_id','regimen','dias_alta','jornadas_reales',
        'coef_aplicado','dias_equivalentes','observaciones',
    ];

    protected $casts = [
        'coef_aplicado' => 'decimal:4',
    ];
}

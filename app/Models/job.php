<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'jobs';
    public $timestamps = false;

    protected $fillable = [
        'company_id','title','type','location',
        'salary_min','salary_max','description',
        'status','posted_at',
    ];

    protected $casts = [
        'posted_at' => 'date',
        'salary_min' => 'integer',
        'salary_max' => 'integer',
    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }
}

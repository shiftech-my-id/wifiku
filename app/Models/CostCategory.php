<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CostCategory extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'description'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

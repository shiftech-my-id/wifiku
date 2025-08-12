<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cost extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'category_id',
        'datetime',
        'description',
        'amount',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'company_id' => 'integer',
            'category_id' => 'integer',
            'datetime' => 'datetime',
            'amount' => 'decimal:2',
            'notes' => 'string',
            'created_at' => 'datetime',
            'created_by' => 'integer',
            'updated_at' => 'datetime',
            'updated_by' => 'integer',
            'deleted_at' => 'datetime',
            'deleted_by' => 'integer',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function category()
    {
        return $this->belongsTo(CostCategory::class, 'category_id');
    }
}

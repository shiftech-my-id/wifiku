<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'bill_period',
        'price',
        'active',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];

    const BillPeriod_Monthly   = 'monthly';
    const BillPeriod_Yearly   = 'yearly';

    const BillPeriods = [
        self::BillPeriod_Monthly   => 'Bulanan',
        self::BillPeriod_Yearly => 'Tahunan',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

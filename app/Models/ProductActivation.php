<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductActivation extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'customer_id',
        'product_id',
        'datetime',
        'price',
        'bill_period',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'user_id' => 'integer',
        'customer_id' => 'integer',
        'product_id' => 'integer',
        'datetime' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

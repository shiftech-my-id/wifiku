<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'product_id',
        'code',
        'name',
        'id_card_number',
        'email',
        'phone',
        'wa',
        'address',
        'active',
        'balance',
        'installation_date',
        'lat_long',
        'notes',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'product_id' => 'integer',
        'active' => 'boolean',
        'balance' => 'decimal:2',
        'installation_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

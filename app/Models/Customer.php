<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'company_id',
        'product_id',
        'code', // Ganti ke properti dinamis dengan prefix custom, misal CST-[customer_id]
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
        'customer_id' => 'integer',
        'company_id' => 'integer',
        'product_id' => 'integer',
        'active' => 'boolean',
        'balance' => 'decimal:2',
        'installation_date' => 'date',
    ];

    protected $appends = ['code'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Accessor untuk properti code (tidak disimpan di database)
     * Output: CST-00000123
     */
    public function getCodeAttribute(): string
    {
        return 'CST-' . str_pad((string) $this->customer_id, 8, '0', STR_PAD_LEFT);
    }

    /**
     * Opsional: fungsi helper untuk generate customer_id selanjutnya
     */
    public static function getNextCustomerId($companyId)
    {
        $maxCustomerId = self::where('company_id', $companyId)->max('customer_id');
        return ($maxCustomerId ?? 0) + 1;
    }
}

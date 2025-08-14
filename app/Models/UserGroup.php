<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserGroup extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

abstract class BaseModel extends Model
{
    use SoftDeletes;

    // Nonaktifkan default timestamps bawaan Laravel
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        // Optional: auto-scope untuk user_id
        static::addGlobalScope('user', function ($query) {
            if (Auth::check() && Schema::hasColumn((new static)->getTable(), 'user_id')) {
                $query->where('user_id', Auth::id());
            }
        });

        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'created_at')) {
                $model->created_at = now();
            }

            if (Schema::hasColumn($model->getTable(), 'created_by') && Auth::check()) {
                $model->created_by = Auth::id();
            }

            if (Schema::hasColumn($model->getTable(), 'user_id') && Auth::check()) {
                $model->user_id = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'updated_at')) {
                $model->updated_at = now();
            }

            if (Schema::hasColumn($model->getTable(), 'updated_by') && Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleting(function ($model) {
            if ($model->usesSoftDeletes()) {
                if (Schema::hasColumn($model->getTable(), 'deleted_at')) {
                    $model->deleted_at = now();
                }

                if (Schema::hasColumn($model->getTable(), 'deleted_by') && Auth::check()) {
                    $model->deleted_by = Auth::id();
                }

                // Penting: simpan perubahan deleted_*
                $model->save();
            }
        });
    }

    protected function usesSoftDeletes(): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive(static::class));
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

abstract class BaseModel extends Model
{
    // Nonaktifkan default timestamps bawaan Laravel
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        // GLOBAL SCOPE: company_id
        static::addGlobalScope('company', function ($query) {
            if (Auth::check() && static::hasColumn('company_id')) {
                $query->where('company_id', Auth::user()->company_id);
            }
        });

        static::creating(function ($model) {
            $now = now();
            $auth = Auth::user();

            if ($auth) {
                if ($model->hasColumn('user_id')) {
                    $model->user_id = $auth->id;
                }

                if ($model->hasColumn('created_by')) {
                    $model->created_by = $auth->id;
                }

                if ($model->hasColumn('company_id')) {
                    $model->company_id = $auth->company_id;
                }
            }

            if ($model->hasColumn('created_at')) {
                $model->created_at = $now;
            }
        });

        static::updating(function ($model) {
            $now = now();
            $auth = Auth::user();

            if ($model->hasColumn('updated_at')) {
                $model->updated_at = $now;
            }

            if ($auth && $model->hasColumn('updated_by')) {
                $model->updated_by = $auth->id;
            }
        });

        static::deleting(function ($model) {
            if ($model->usesSoftDeletes()) {
                $now = now();
                $auth = Auth::user();

                $changed = false;

                if ($model->hasColumn('deleted_at')) {
                    $model->deleted_at = $now;
                    $changed = true;
                }

                if ($auth && $model->hasColumn('deleted_by')) {
                    $model->deleted_by = $auth->id;
                    $changed = true;
                }

                if ($changed) {
                    $model->save();
                }
            }
        });
    }

    protected static function hasColumn(string $column): bool
    {
        static $columnsCache = [];

        $table = (new static)->getTable();

        if (!isset($columnsCache[$table])) {
            $columnsCache[$table] = \Illuminate\Support\Facades\Schema::getColumnListing($table);
        }

        return in_array($column, $columnsCache[$table]);
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

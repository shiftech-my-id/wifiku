<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}

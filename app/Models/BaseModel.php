<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder; // Import Builder for type hinting
use Illuminate\Support\Facades\Schema; // Import Schema for getColumnListing

abstract class BaseModel extends Model
{
    // Nonaktifkan default timestamps bawaan Laravel
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        // GLOBAL SCOPE: company_id
        // Scope ini akan diterapkan secara kondisional.
        // PENTING: Jika model yang sedang di-boot adalah model User DAN authentication guard
        // belum memiliki user yang dimuat (menandakan proses autentikasi awal),
        // scope ini akan dilewati untuk mencegah rekursi tak terbatas.
        static::addGlobalScope('company', function (Builder $query) {
            // Memastikan kolom 'company_id' ada di tabel model saat ini.
            if ((new static)->hasColumn('company_id')) {
                // Periksa apakah model yang sedang dikueri adalah model User
                // DAN authentication guard belum memiliki user yang dimuat.
                // Kondisi ini secara efektif menandakan bahwa kita sedang dalam
                // proses autentikasi awal user.
                if ($query->getModel() instanceof \App\Models\User && ! Auth::guard()->hasUser()) {
                    return; // Lewati scope ini untuk model User selama proses autentikasi awal.
                    // Laravel akan menangani filter company_id melalui kredensial di Auth::attempt().
                }

                // Untuk semua kasus lain (model non-User, atau model User setelah autentikasi berhasil):
                // Terapkan filter company_id jika ada user yang terautentikasi.
                // Pada titik ini, Auth::user() aman dipanggil karena user sudah dimuat
                // oleh authentication guard.
                if (Auth::check()) {
                    $query->where('company_id', Auth::user()->company_id);
                }
            }
        });

        // Event listener untuk saat model sedang dibuat (creating)
        static::creating(function ($model) {
            $now = now();
            $auth = Auth::user();

            if ($auth) {
                // Set user_id jika kolom ada
                if ($model->hasColumn('user_id')) {
                    $model->user_id = $auth->id;
                }

                // Set created_by jika kolom ada
                if ($model->hasColumn('created_by')) {
                    $model->created_by = $auth->id;
                }

                // Set company_id jika kolom ada
                // Pengecualian untuk model User di sini juga penting,
                // karena company_id user biasanya ditetapkan saat registrasi
                // atau secara manual, bukan dari Auth::user()->company_id saat creating.
                if ($model->hasColumn('company_id') && !($model instanceof \App\Models\User)) {
                    $model->company_id = $auth->company_id;
                }
            }

            // Set created_at jika kolom ada
            if ($model->hasColumn('created_at')) {
                $model->created_at = $now;
            }
        });

        // Event listener untuk saat model sedang diperbarui (updating)
        static::updating(function ($model) {
            $now = now();
            $auth = Auth::user();

            // Set updated_at jika kolom ada
            if ($model->hasColumn('updated_at')) {
                $model->updated_at = $now;
            }

            // Set updated_by jika user terautentikasi dan kolom ada
            if ($auth && $model->hasColumn('updated_by')) {
                $model->updated_by = $auth->id;
            }
        });
    }

    /**
     * Memeriksa apakah model memiliki kolom tertentu.
     * Menggunakan cache untuk performa.
     *
     * @param string $column Nama kolom yang ingin diperiksa.
     * @return bool
     */
    protected static function hasColumn(string $column): bool
    {
        static $columnsCache = [];

        $table = (new static)->getTable();

        if (!isset($columnsCache[$table])) {
            $columnsCache[$table] = Schema::getColumnListing($table);
        }

        return in_array($column, $columnsCache[$table]);
    }

    /**
     * Relasi dengan model User untuk creator (created_by).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi dengan model User untuk updater (updated_by).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relasi dengan model User untuk deleter (deleted_by).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}

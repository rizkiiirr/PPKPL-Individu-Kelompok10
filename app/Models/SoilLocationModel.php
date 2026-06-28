<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoilLocationModel extends Model
{
    protected $table = 'j1_jadwal_titik_uji';

    protected $fillable = [
        'pengajuan_id',
        'petugas_lapangan_id',
        'latitude',
        'longitude',
        'tanggal_uji',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'tanggal_uji' => 'date',
    ];

    // Menghubungkan Titik Koordinat kembali ke Pengajuan
    public function soilTest(): BelongsTo
    {
        return $this->belongsTo(SoilTestModel::class, 'pengajuan_id');
    }

    public function petugasLapangan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_lapangan_id');
    }

    public function hasilSondir()
    {
        return $this->hasOne(
            HasilSondirModel::class,
            'lokasi_id'
        );
    }
}
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SoilTestModel extends Model
{
    protected $table = 'j1_pengajuan_uji_tanah';

    protected $fillable = [
        'proyek_id',
        'kontraktor_id',
        'jenis_pengujian',
        'status',
    ];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(ProyekModel::class, 'proyek_id');
    }

    public function kontraktor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kontraktor_id');
    }

    // Menghubungkan Pengajuan ke Titik Koordinat
    public function location(): HasOne
    {
        return $this->hasOne(SoilLocationModel::class, 'pengajuan_id');
    }

    // public function proyek()
    // {
    //     return $this->belongsTo(
    //         ProyekModel::class,
    //         'proyek_id'
    //     );
    // }

    public function hasilSondir()
    {
        return $this->hasOne(
            HasilSondirModel::class,
            'soil_test_id'
        );
    }

    public function soilCertificate()
    {
        return $this->hasOne(
            SoilCertificate::class,
            'pengajuan_uji_tanah_id'
        );
    }
}
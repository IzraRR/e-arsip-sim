<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';
    
    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tujuan',
        'perihal',
        'file_surat',
        'penandatangan',
        'status',
        'prioritas',
        'sifat',
        'keterangan',
        'user_id'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
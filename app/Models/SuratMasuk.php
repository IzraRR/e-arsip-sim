<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuk';
    
    protected $fillable = [
        'nomor_agenda',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_terima',
        'pengirim',
        'perihal',
        'file_surat',
        'status',
        'prioritas',
        'sifat',
        'keterangan',
        'user_id'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function disposisi()
    {
        return $this->hasMany(Disposisi::class, 'surat_masuk_id');
    }
}
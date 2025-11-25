<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    protected $table = 'disposisi';
    
    protected $fillable = [
        'surat_masuk_id',
        'dari_user_id',
        'kepada_user_id',
        'instruksi',
        'tanggal_disposisi',
        'batas_waktu',
        'status',
        'catatan',
        'file_lampiran'
    ];

    protected $casts = [
        'tanggal_disposisi' => 'date',
        'batas_waktu' => 'date',
    ];

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }

    public function dariUser()
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    public function kepadaUser()
    {
        return $this->belongsTo(User::class, 'kepada_user_id');
    }
    public function penerima()
    {
        // GANTI 'tujuan_id' sesuai nama kolom foreign key di database Anda
        return $this->belongsTo(User::class, 'kepada_user_id'); 
    }
}
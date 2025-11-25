<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    protected $table = 'arsip';
    
    protected $fillable = [
        'nomor_dokumen',
        'judul',
        'kategori_id',
        'tanggal_dokumen',
        'file_dokumen',
        'keterangan',
        'tags',
        'user_id'
    ];

    protected $casts = [
        'tanggal_dokumen' => 'date',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
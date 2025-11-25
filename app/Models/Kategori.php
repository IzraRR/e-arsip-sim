<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    
    protected $fillable = [
        'kode',
        'nama_kategori',
        'deskripsi'
    ];

    public function arsip()
    {
        return $this->hasMany(Arsip::class, 'kategori_id');
    }
}
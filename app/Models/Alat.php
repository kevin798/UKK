<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jumlah',
        'keterangan',
        'gambar',
    ];

    public function kategori()
    {
        return $this->belongsToMany(Kategori::class, 'alat_kategori');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'alat_id');
    }
}

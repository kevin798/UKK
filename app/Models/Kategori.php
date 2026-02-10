<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function alats()
    {
        return $this->belongsToMany(Alat::class, 'alat_kategori');
    }
}

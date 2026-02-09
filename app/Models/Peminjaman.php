<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id',
        'alat_id',
        'jumlah',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'keterangan',
        'denda_amount',
        'denda_status',
        'denda_type',
        'denda_reason',
        'denda_set_by',
        'keterlambatan_hari',
        'kondisi_pengembalian',
        'gambar_pengembalian',
        'catatan_pengembalian',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }

    public function dendaSetter()
    {
        return $this->belongsTo(User::class, 'denda_set_by');
    }
}

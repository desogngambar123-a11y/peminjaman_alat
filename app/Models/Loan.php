<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id',
        'alat',
        'nama_alat',
        'foto',
        'foto_alat',
        'foto_url',
        'borrower',
        'tanggal_pinjam',
        'status',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
    ];
}

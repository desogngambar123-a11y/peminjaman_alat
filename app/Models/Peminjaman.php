<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_pinjam' => 'date',
    ];

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'loan_id')->oldest();
    }
}
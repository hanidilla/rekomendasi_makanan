<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanMakanan extends Model
{
    use HasFactory;

    protected $table = 'bahan_makanan';

    protected $fillable = [
        'bahan_makanan',
        'berat',
        'energi',
        'protein',
        'lemak',
        'karbohidrat',
        'kandungan_makanan',
        'type'
    ];
}

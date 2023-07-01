<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Probabilitas extends Model
{
    use HasFactory;

    protected $table = 'probabilitas';

    protected $fillable = [
        'kebutuhan_gizi_id',
        'probabilitas',
        'kategori_makanan'
    ];
}

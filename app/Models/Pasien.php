<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien';

    protected $fillable = [
        'nama',
        'umur',
        'jenis_kelamin',
        'tinggi_badan',
        'berat_badan',
        'kode',
    ];
}

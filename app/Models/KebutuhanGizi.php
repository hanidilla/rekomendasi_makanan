<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebutuhanGizi extends Model
{
    use HasFactory;

    protected $table = 'kebutuhan_gizi';

    protected $fillable = [
        'user_id',
        'umur',
        'tinggi',
        'berat',
        'stress_fac',
        'activity_fac',
        'kalori',
        'protein',
        'lemak',
        'karbohidrat',
    ];


    /**
     * Get the user associated with the KebutuhanGizi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pasien()
    {
        return $this->hasOne(Pasien::class, 'id', 'user_id');
    }
}

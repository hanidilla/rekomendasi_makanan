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


    /**
     * Get the gizi associated with the Probabilitas
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function gizi()
    {
        return $this->hasOne(KebutuhanGizi::class,  'id', 'kebutuhan_gizi_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaranMakanan extends Model
{
    use HasFactory;

    protected $table = 'saran_makanan';

    protected $fillable = [
        'kebutuhan_gizi_id',
        'saran_makanan'
    ];

    /**
     * Get the gizi associated with the SaranMakanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function gizi()
    {
        return $this->hasOne(KebutuhanGizi::class,  'id', 'kebutuhan_gizi_id');
    }
}

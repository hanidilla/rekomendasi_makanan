<?php

namespace App\Http\Controllers;

use App\Models\FaktorAktivitas;
use App\Models\FaktorStress;
use App\Models\KoreksiUmur;
use App\Traits\Response;
use Illuminate\Http\Request;

class FaktorController extends Controller
{
    use Response;
    //

    public function getAllFactor()
    {
        $faktorAktivitas = FaktorAktivitas::all();
        $faktorStress = FaktorStress::all();
        $koreksiUmur = KoreksiUmur::all();

        return $this->success([
            'faktor_aktivitas' => $faktorAktivitas,
            'faktor_stress' => $faktorStress,
            'koreksi_umur' => $koreksiUmur,
        ], 'Berhasil mendapatkan semua faktor');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\KebutuhanGizi;
use App\Models\KoreksiUmur;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\NaiveBayesController as NVB;
use App\Models\Probabilitas;
use App\Models\SaranMakanan;
use App\Models\Pasien;

class KebutuhanGiziController extends Controller
{
    
    use Response;
    //

    public function index()
    {
        try {
            $kebutuhanGizi = KebutuhanGizi::with('pasien')->get();
            return $this->success($kebutuhanGizi, 'Berhasil Mendapatkan Semua Kebutuhan Gizi');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();

            $pasien = Pasien::where('id',$request->user_id)->first();

            $data['jenis_kelamin'] = $pasien->jenis_kelamin;
            $data['user_id'] = $request->user_id;

            $kalori = $this->calKalori($data, $request->stress_fac, $request->activity_fac, $this->ageCor($request->umur));
            $data['kalori'] = number_format($kalori, 2, '.', '');

            $protein = $this->calProtein($data, $request->stress_fac, $request->activity_fac, $this->ageCor($request->umur));
            $data['protein'] = number_format($protein, 2, '.', '');

            $lemak = $this->calLemak($data, $request->stress_fac, $request->activity_fac, $this->ageCor($request->umur));
            $data['lemak'] = number_format($lemak, 2, '.', '');

            $karbohidrat = $this->calKarbohidrat($data, $request->stress_fac, $request->activity_fac, $this->ageCor($request->umur));
            $data['karbohidrat'] = number_format($karbohidrat, 2, '.', '');

            $nvb = new NVB();
            $payload = [
                "protein" => $data["protein"],
                "lemak" => $data["lemak"],
                "karbohidrat" => $data["karbohidrat"]
            ];

            $res = $nvb->nvBayes($payload);
            $kebutuhanGizi = KebutuhanGizi::create($data);
            SaranMakanan::create([
                "kebutuhan_gizi_id" => $kebutuhanGizi["id"],
                "data" => json_encode($res),
            ]);
            DB::commit();
            return $this->success($kebutuhanGizi, 'Kebutuhan Gizi Berhasil Dibuat');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->error($th->getMessage(), 500);
        }
    }


    public function ageCor($umur)
    {
        $umurCor = KoreksiUmur::all();
        foreach ($umurCor as $key => $value) {
            # code...
            if (str_contains($value->nama, '-')) {
                $umurTest = explode('-', $value->nama);
                $umur_min = $umurTest[0];
                // dd($umur_min);
                $umur_max = $umurTest[1];
                if ($umur >= $umur_min && $umur <= $umur_max) {
                    # code...
                    return $value->presentase;
                }
            } else {
                $umurTestleb = preg_replace('/[^\p{L}\p{N}\s]/u', '', $value->name);
                if ($umur == $umurTestleb) {
                    # code...
                    return $value->presentase;
                }
            }
        }
    }
    public function calIdealWB($data)
    {
        if($data['jenis_kelamin'] == 'laki-laki')
        {
            return sqrt(($data['tinggi'] / 100)) * 22.5; // laki laki
        }else
        {
            return sqrt(($data['tinggi'] / 100)) * 21; // perempuan
        } 
    }

    public function calBasal($data)
    {
        if($data['jenis_kelamin'] == 'laki-laki')
        {
            return $this->calIdealWB($data) * 30; // laki laki
        }else
        {
            return $this->calIdealWB($data) * 25; // perempuan
        }
    }

    public function calKalori($data, $stress_fac, $activity_fac, $age)
    {
        # code...
        return $this->calBasal($data) + ($this->calBasal($data) * ($activity_fac + $stress_fac - $age));
    }

    public function calLemak($data, $stress_fac, $activity_fac, $age)
    {
        # code...
        return $this->calKalori($data, $stress_fac, $activity_fac, $age) * ($data['berat'] > $this->calIdealWB($data) ? 0.1 : 0.15)  / 9;
    }

    public function calProtein($data, $stress_fac, $activity_fac, $age)
    {
        # code...
        return $this->calKalori($data, $stress_fac, $activity_fac, $age) * ($data['berat'] > $this->calIdealWB($data) ? 0.2 : 0.25)  / 4;
    }

    public function calKarbohidrat($data, $stress_fac, $activity_fac, $age)
    {
        # code...
        return $this->calKalori($data, $stress_fac, $activity_fac, $age) * ($data['berat'] > $this->calIdealWB($data) ? 0.6 : 0.7)  / 4;
    }
}

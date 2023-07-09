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

class KebutuhanGiziController extends Controller
{
    use Response;
    //

    public function index()
    {
        try {
            $kebutuhanGizi = KebutuhanGizi::with('pasien')->get();
            // dd($kebutuhanGizi);
            return $this->success($kebutuhanGizi, 'Berhasil Mendapatkan Semua Kebutuhan Gizi');
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return $this->error($th->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        # code...
        try {
            DB::beginTransaction();
            $data = $request->all();
            // $data['user_id'] = auth()->user()->id;
            $data['user_id'] = $request->user_id;
            $data['kalori'] = number_format($this->calKalori($data, $request->stress_fac, $request->activity_fac, $this->ageCor($request->umur)), 2, '.', '');
            $data['protein'] = number_format($this->calProtein($data, $request->stress_fac, $request->activity_fac, $this->ageCor($request->umur)), 2, '.', '');
            $data['lemak'] = number_format($this->calLemak($data, $request->stress_fac, $request->activity_fac, $this->ageCor($request->umur)), 2, '.', '');
            $data['karbohidrat'] = number_format($this->calKarbohidrat($data, $request->stress_fac, $request->activity_fac, $this->ageCor($request->umur)), 2, '.', '');

            $kebutuhanGizi = KebutuhanGizi::create($data);
            $nvb = new NVB();
            $payload = [
                "protein" => $data["protein"],
                "lemak" => $data["lemak"],
                "karbohidrat" => $data["karbohidrat"]
            ];
            $res = $nvb->nvBayes($payload);
            // dd(json_encode($res), $data);
            // foreach ($res as $key => $value) {
            # code...
            // Probabilitas::create([
            //     "kebutuhan_gizi_id" => $kebutuhanGizi["id"],
            //     "probabilitas" =>  $value,
            //     "kategori_makanan" => $key
            // ]);
            SaranMakanan::create([
                "kebutuhan_gizi_id" => $kebutuhanGizi["id"],
                "saran_makanan" => json_encode($res)
            ]);
            // }

            DB::commit();
            return $this->success($kebutuhanGizi, 'Kebutuhan Gizi Berhasil Dibuat');
            //code...
        } catch (\Throwable $th) {
            //throw $th;
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
        return sqrt(($data['tinggi'] / 100)) * 22.5;
    }

    public function calBasal($data)
    {
        # code...
        return $this->calIdealWB($data) * 30;
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

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
use Carbon\Carbon;
class KebutuhanGiziController extends Controller
{
    
    use Response;
    //

    public function index()
    {
        try {
            $kebutuhanGizi = KebutuhanGizi::with('pasien')->get();
            $kebutuhanGizi = json_decode(json_encode($kebutuhanGizi),true);
            foreach ($kebutuhanGizi as $key => $value) 
            {
               $age = $this->ageCor($value['umur']);
               $kebutuhanGizi[$key]['umur_prosentanse'] = $value['umur'].' : ('.$age.')';
            }
           // dd($kebutuhanGizi);
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

            $data['bb_ideal'] = $this->callBbIdeal($data);
            $data['bmr'] = $this->callBmr($data);

            $data['user_id'] = $request->user_id;

            //$kalori = $this->calKalori($data, $request->stress_fac, $request->activity_fac, $this->ageCor($request->umur));
            //$data['kalori'] = number_format($kalori, 2, '.', '');

            $data['kalori'] = $this->calKaloriNew($data);

            // $protein = $this->calProtein($data, $request->stress_fac, $request->activity_fac, $this->ageCor($request->umur));
            // $data['protein'] = number_format($protein, 2, '.', '');

            $data['protein'] = $this->calProteinNew($data);

            // $lemak = $this->calLemak($data, $request->stress_fac, $request->activity_fac, $this->ageCor($request->umur));
            // $data['lemak'] = number_format($lemak, 2, '.', '');

            $data['lemak'] = $this->calLemakNew($data);

            // $karbohidrat = $this->calKarbohidrat($data, $request->stress_fac, $request->activity_fac, $this->ageCor($request->umur));
            // $data['karbohidrat'] = number_format($karbohidrat, 2, '.', '');

            $data['karbohidrat'] = $this->calKarbohidratNew($data);

            //kode  
            $count = DB::table('kebutuhan_gizi')->where('user_id',$request->user_id)->count() + 1;
            $user = DB::table('pasien')->where('id',$request->user_id)->select('kode')->first();
            $kode = $user->kode.'-'.$count.Carbon::now('Asia/Jakarta')->format('Ymd');
            $data['kode'] = $kode;
            $nvb = new NVB();
            $payload = [
                "protein" => $data["protein"],
                "lemak" => $data["lemak"],
                "karbohidrat" => $data["karbohidrat"],
                "kalori"=>$data["kalori"]
            ];

            $res = $nvb->nvBayes($payload,$data);
           // dd($res['arr']);
            $kebutuhanGizi = KebutuhanGizi::create($data);
            SaranMakanan::create([
                "kebutuhan_gizi_id" => $kebutuhanGizi["id"],
                "data" => json_encode($res['arr']),
                //"arr_id" => implode(',', $res['id']),
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
        foreach ($umurCor as $key => $value) 
        {
            $text = str_replace('tahun', '', $value->nama);
            $text = str_replace('umur', '', $text);
            $text = str_replace('>', '', $text);
            $text = preg_replace('/\s+/', '', $text);
            $exp = explode('-', $text);
            if(isset($exp[0]))
            {
                $min = $exp[0];
                $max = 0;
                if(isset($exp[1]))
                {
                    $max = $exp[1];
                    if ($umur >= $min && $umur <= $max) 
                    {
                        return $value->presentase;
                    }
                }else
                {
                    if($umur >= $min)
                    {
                        return $value->presentase;
                    }
                }
            }
        }
        return 0.2;
    }

    public function callBbIdeal($data)
    {
        $tinggi = $data['tinggi'] / 100;
        $bbIdeal = 0;
        if($data['jenis_kelamin'] == 'laki-laki')
        {
            $bbIdeal = $tinggi * $tinggi * 22.5;
        }else
        {
            $bbIdeal = $tinggi * $tinggi * 21;
        }

        return $bbIdeal;
    }

    public function callBmr($data)
    {
        $bbIdeal = $data['bb_ideal'];
        $bmr = 0;
        if($data['jenis_kelamin'] == 'laki-laki')
        {
            $bmr = $bbIdeal * 30;
        }else
        {
            $bmr = $bbIdeal * 25;
        }

        return $bmr;
    }

    public function calKaloriNew($data)
    {
        $umur = $this->ageCor($data['umur']);
        $kalori = $data['bmr'] + $data['bmr'] * ($data['activity_fac'] + $data['stress_fac'] - $umur);
        return $kalori;
    }

    public function calProteinNew($data)
    {
        $kalori = $data['kalori'];
        $persen = 20 / 100;
        $protein = $kalori * $persen / 4;
        return $protein;
    }

    public function calLemakNew($data)
    {
        $kalori = $data['kalori'];
        $persen = 20 / 100;
        $lemak = $kalori * $persen / 9;
        return $lemak;
    }

    public function calKarbohidratNew($data)
    {
        $kalori = $data['kalori'];
        $persen = 60 / 100;
        $karbo = $kalori * $persen / 4;
        return $karbo;
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

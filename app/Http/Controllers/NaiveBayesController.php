<?php

namespace App\Http\Controllers;

use App\Models\BahanMakanan;
use App\Models\KebutuhanGizi;
use App\Models\Probabilitas;
use App\Models\SaranMakanan;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MathPHP\Probability\Distribution\Continuous;
use Carbon\Carbon;
class NaiveBayesController extends Controller
{
    
    //
    use Response;
    public function probability()
    {
        try {
            $data = DB::select('SELECT kandungan_makanan, COUNT("kandungan_makanan") as jumlah  FROM bahan_makanan GROUP BY kandungan_makanan');
            $length = BahanMakanan::all();
            $kategori = [];

            foreach ($data as $key => $value) {
                $kategori[$value->kandungan_makanan] = number_format($value->jumlah / count($length), 2, '.', '');
            }
            // dd($kategori);
            return $kategori;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getDataBahan()
    {
        $data = DB::select('SELECT kandungan_makanan, COUNT("kandungan_makanan") as jumlah  FROM bahan_makanan GROUP BY kandungan_makanan');
        $kategori = [];
        foreach ($data as $key => $value) 
        {
            $kategori[$value->kandungan_makanan] = $value->jumlah;
        }
        return $kategori;
    }

    public function getDataKategori()
    {
        $data = BahanMakanan::all();
        $bahan = [];
        $kategori = [];

        foreach ($data as $key => $value) {
            if (!in_array($value->kandungan_makanan, $bahan) || count($bahan) == 0) {
                array_push($bahan, $value->kandungan_makanan);

                $kategori[$value->kandungan_makanan] =
                    ["protein" => $value->protein, "lemak" => $value->lemak, "karbohidrat" => $value->karbohidrat];
            } else if (in_array($value->kandungan_makanan, $bahan)) {

                switch ($value->kandungan_makanan) {
                    case 'protein':
                        # code...
                        $kategori["protein"]["protein"] += $value->protein;
                        $kategori["protein"]["karbohidrat"] += $value->karbohidrat;
                        $kategori["protein"]["lemak"] += $value->lemak;
                        break;
                    case 'lemak':
                        # code...
                        $kategori["lemak"]["protein"] += $value->protein;
                        $kategori["lemak"]["karbohidrat"] += $value->karbohidrat;
                        $kategori["lemak"]["lemak"] += $value->lemak;
                        break;
                    case 'karbohidrat':
                        # code...
                        $kategori["karbohidrat"]["protein"] += $value->protein;
                        $kategori["karbohidrat"]["karbohidrat"] += $value->karbohidrat;
                        $kategori["karbohidrat"]["lemak"] += $value->lemak;
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }

        return $kategori;
    }



    public function mean()
    {
        try {

            $dataBahan = $this->getDataBahan();

            $dataMean = [];
            // dd($kategori);
            $kategori = $this->getDataKategori();
            foreach ($kategori as $k => $val) {
                # code...
                $dataMean[$k] = ["protein" => $val["protein"] / $dataBahan[$k], "lemak" => $val["lemak"] / $dataBahan[$k], "karbohidrat" => $val["karbohidrat"] / $dataBahan[$k]];
            }

            return $dataMean;
        } catch (\Throwable $th) {
        }
    }

    public function deviasi()
    {
        $data = BahanMakanan::all();
        $dataBahan = $this->getDataBahan();
        $dataMean = $this->mean();
        $bahan = [];
        $kategori = [];
        $dataVariance = [];
        // dd($dataBahan, $dataMean);
        foreach ($data as $key => $value) {
            if (!in_array($value->kandungan_makanan, $bahan) || count($bahan) == 0) {
                array_push($bahan, $value->kandungan_makanan);

                $dataVariance[$value->kandungan_makanan] =
                    ["protein" => pow(((float) $value->protein - $dataMean[$value->kandungan_makanan]["protein"]), 2), "lemak" => pow(((float) $value->lemak - $dataMean[$value->kandungan_makanan]["lemak"]), 2), "karbohidrat" => pow(((float) $value->karbohidrat - $dataMean[$value->kandungan_makanan]["karbohidrat"]), 2)];
            } else if (in_array($value->kandungan_makanan, $bahan)) {
                switch ($value->kandungan_makanan) {
                    case 'protein':
                        # code...
                        $dataVariance["protein"]["protein"] += pow(((float) $value->protein - $dataMean["protein"]["protein"]), 2);
                        $dataVariance["protein"]["karbohidrat"] += pow(((float) $value->karbohidrat - $dataMean["protein"]["karbohidrat"]), 2);
                        $dataVariance["protein"]["lemak"] += pow(((float) $value->lemak - $dataMean["protein"]["lemak"]), 2);
                        break;
                    case 'lemak':

                        $dataVariance["lemak"]["protein"] += pow(((float) $value->protein - $dataMean["lemak"]["protein"]), 2);
                        $dataVariance["lemak"]["karbohidrat"] += pow(((float) $value->karbohidrat - $dataMean["lemak"]["karbohidrat"]), 2);
                        $dataVariance["lemak"]["lemak"] += pow(((float) $value->lemak - $dataMean["lemak"]["lemak"]), 2);
                        break;
                    case 'karbohidrat':
                        # code...
                        $dataVariance["karbohidrat"]["protein"] += pow(((float) $value->protein - $dataMean["karbohidrat"]["protein"]), 2);
                        $dataVariance["karbohidrat"]["karbohidrat"] += pow(((float) $value->karbohidrat - $dataMean["karbohidrat"]["karbohidrat"]), 2);
                        $dataVariance["karbohidrat"]["lemak"] += pow(((float) $value->lemak - $dataMean["karbohidrat"]["lemak"]), 2);
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }


        $dataStdev = [];
        foreach ($dataVariance as $k => $v) {
            $dataStdev[$k] = ["protein" => sqrt($v["protein"] / $dataBahan[$k]), "lemak" => sqrt($v["lemak"] / $dataBahan[$k]), "karbohidrat" => sqrt($v["karbohidrat"] / $dataBahan[$k])];
        }
        // dd($dataVariance, $dataStdev);

        return $dataStdev;
    }

    public function normalDist($karbo, $protein, $lemak)
    {
        $mean = $this->mean();
        $stdev = $this->deviasi();
        $dataNormal = [];
        // dd(\stats_dens_normal);
        foreach ($mean as $k => $v) {
            // dd($k);
            // $normalProt = new Continuous\Normal($v["protein"], $stdev[$k]["protein"]);
            // $normalKarbo = new Continuous\Normal($v["karbohidrat"], $stdev[$k]["karbohidrat"]);
            // $normalLemak = new Continuous\Normal($v["lemak"], $stdev[$k]["lemak"]);
            $dataNormal[$k] = ["protein" => $this->distNorm($protein, $v["protein"], $stdev[$k]["protein"]), "lemak" => $this->distNorm($lemak, $v["lemak"], $stdev[$k]["lemak"]), "karbohidrat" => $this->distNorm($karbo, $v["karbohidrat"], $stdev[$k]["karbohidrat"])];
        }
        // return $this->success($dataNormal, "Sukses");
        return $dataNormal;
    }

    public function distNorm($x, $mean, $sd)
    {
        $prob = (pi() * $sd) * exp(-0.5 * (($x - $mean) / $sd) ** 2);
        return $prob;
    }

    public function naiveBayes($payload)
    {
        $prob = $this->probability();
        // dd($prob);
        // $dataNorm = $this->normalDist();
        $dataNorm = $this->normalDist($payload['karbohidrat'], $payload['protein'], $payload['lemak']);
        $res = [];
        foreach ($dataNorm as $key => $value) {
            // dd($value["protein"] * $value["lemak"] * $value["karbohidrat"]);
            $res[$key] = $value["protein"] * $value["lemak"] * $value["karbohidrat"] * (float) $prob[$key];
        }
        return $res;
    }


    public function nvBayes($payload)
    {
        $dataKarbo = DB::select('SELECT * FROM bahan_makanan WHERE kandungan_makanan = "karbohidrat"');
        $dataLemak = DB::select('SELECT * FROM bahan_makanan WHERE kandungan_makanan = "lemak"');
        $dataProtein = DB::select('SELECT * FROM bahan_makanan WHERE kandungan_makanan = "protein"');

        $dataMakanan = DB::select('SELECT * FROM bahan_makanan');

        $makanan = [];
        $jmlKarbo = 0;
        $jmlLemak = 0;
        $jmlProtein = 0;
        $keyKarbo = -1;
        $keyProtein = -1;
        $keyLemak = -1;
        foreach ($dataMakanan as $key => $value) {
            if ($value->kandungan_makanan == "karbohidrat") {
                if ($jmlKarbo + $value->karbohidrat <= $payload['karbohidrat']) {
                    $jmlKarbo += $value->karbohidrat;
                    $jmlLemak += $value->lemak;
                    $jmlProtein += $value->protein;
                    $keyKarbo++;

                    $makanan['karbohidrat'][$keyKarbo]['id'] = $value->id;
                    $makanan['karbohidrat'][$keyKarbo]['bobot'] = $value->karbohidrat;
                    $makanan['karbohidrat'][$keyKarbo]['type'] = $value->type;
                    $makanan['karbohidrat'][$keyKarbo]['bahan_makanan'] = $value->bahan_makanan;
                }
            }

            if ($value->kandungan_makanan == "lemak") {
                if ($jmlLemak + $value->lemak <= $payload['lemak']) {
                    $jmlKarbo += $value->karbohidrat;
                    $jmlLemak += $value->lemak;
                    $jmlProtein += $value->protein;
                    $keyLemak++;

                    $makanan['lemak'][$keyLemak]['id'] = $value->id;
                    $makanan['lemak'][$keyLemak]['bobot'] = $value->lemak;
                    $makanan['lemak'][$keyLemak]['type'] = $value->type;
                    $makanan['lemak'][$keyLemak]['bahan_makanan'] = $value->bahan_makanan;
                }
            }

            if ($value->kandungan_makanan == "protein") {
                if ($jmlProtein + $value->protein <= $payload['protein']) {
                    $jmlKarbo += $value->karbohidrat;
                    $jmlLemak += $value->lemak;
                    $jmlProtein += $value->protein;
                    $keyProtein++;

                    $makanan['protein'][$keyProtein]['id'] = $value->id;
                    $makanan['protein'][$keyProtein]['bobot'] = $value->protein;
                    $makanan['protein'][$keyProtein]['type'] = $value->type;
                    $makanan['protein'][$keyProtein]['bahan_makanan'] = $value->bahan_makanan;
                }
            }
        }

        $arrVal = ['karbohidrat'=>[],'protein'=>[],'lemak'=>[]];
        $jenis = ['makanan_pokok','buah','sayur','lauk_pauk','makanan_pendamping'];
        $noNya = -1;
        foreach ($arrVal as $arrKey => $arrValue) 
        {
            if(isset($makanan[$arrKey]))
            {
                foreach ($makanan[$arrKey] as $makananKey => $makananValue) 
                {
                    
                    foreach ($jenis as $j => $jv) 
                    {
                        if($makananValue['type'] == $jv)
                        {
                            $noNya++;
                            $arrVal[$arrKey][$jv][$noNya] = $makananValue['bobot'];
                        }
                    }
                }
            }
        }

        $bobot = [];
        $arrType = ['karbohidrat','protein','lemak'];
        foreach ($arrType as $key => $value) 
        {
            foreach ($jenis as $j => $jv) 
            {
                if(isset($arrVal[$value][$jv]))
                {
                    $max = [];
                    foreach ($arrVal[$value][$jv] as $i => $v) 
                    {
                        $max[$i] = $v;
                    }
                    $bobot[$value][$jv] = 0;
                    if(count($max) > 0)
                    {
                        $bobot[$value][$jv] = max($max);
                    }
                }
            }
        }

        $fixArr = [];
        foreach ($arrType as $key => $value) 
        {
            if(isset($makanan[$value]))
            {
                foreach ($makanan[$value] as $i => $v) 
                {
                    foreach ($jenis as $j => $jv) 
                    {
                        if(isset($bobot[$value][$jv]))
                        {
                            if($bobot[$value][$jv] > 0)
                            {
                                if($v['type'] == $jv)
                                {
                                    if($v['bobot'] >= $bobot[$value][$jv])
                                    {
                                        $fixArr[$value][$jv] = $v['bahan_makanan'];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $fixArr;
    }

    public function getRes()
    {
        $data = Probabilitas::with('gizi.pasien')->get();
        $dataRet = [];
        $listId = [];
        foreach ($data as $key => $value) {
            if ($dataRet == null || !in_array($value["kebutuhan_gizi_id"], $listId)) {
                # code...
                array_push($listId, $value["kebutuhan_gizi_id"]);
                $dataPush = [
                    "nama" => $value["gizi"]["pasien"]["nama"],
                    "gizi" => Probabilitas::where("kebutuhan_gizi_id", $value["kebutuhan_gizi_id"])->get()
                ];

                array_push($dataRet, $dataPush);
            } else if (!in_array($value["kebutuhan_gizi_id"], $listId)) {
                $dataPush = [
                    "nama" => $value["gizi"]["pasien"]["nama"],
                    "gizi" => Probabilitas::where("kebutuhan_gizi_id", $value["kebutuhan_gizi_id"])->get()
                ];

                array_push($dataRet, $dataPush);
            }
        }
        return $this->success($dataRet, "");
    }

    public function getSaran()
    {
        $data = DB::table('saran_makanan as sm')
                ->join('kebutuhan_gizi as kgz','kgz.id','=','sm.kebutuhan_gizi_id')
                ->select('sm.*','kgz.user_id','umur','tinggi','berat','stress_fac','activity_fac','kalori','protein','lemak','karbohidrat')
                ->get();
        $dataRet = json_decode(json_encode($data),true);

        foreach ($dataRet as $key => $value) 
        {
            $dataRet[$key]['data'] = json_decode($dataRet[$key]['data'],true);
            $pasien = DB::table('pasien')->where('id',$value['user_id'])->first();
            $dataRet[$key]['nama_pasien'] = '';
            $dataRet[$key]['jenis_kelamin'] = '';
            if($pasien)
            {
                $dataRet[$key]['nama_pasien'] = $pasien->nama;
                $dataRet[$key]['jenis_kelamin'] = $pasien->jenis_kelamin;
            }
            $dataRet[$key]['created_at'] = Carbon::parse($dataRet[$key]['created_at'])->format('Y F d H:i:s');
        }
        //dd($dataRet);
        return view('pages.pasien.naive-bayes',compact('dataRet'));
    }
}
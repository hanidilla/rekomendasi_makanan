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
        foreach ($mean as $k => $v) {
            $dataNormal[$k] = ["protein" => $this->distNorm($protein, $v["protein"], $stdev[$k]["protein"]), "lemak" => $this->distNorm($lemak, $v["lemak"], $stdev[$k]["lemak"]), "karbohidrat" => $this->distNorm($karbo, $v["karbohidrat"], $stdev[$k]["karbohidrat"])];
        }
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
        $dataNorm = $this->normalDist($payload['karbohidrat'], $payload['protein'], $payload['lemak']);
        $res = [];
        foreach ($dataNorm as $key => $value) {
            $res[$key] = $value["protein"] * $value["lemak"] * $value["karbohidrat"] * (float) $prob[$key];
        }
        return $res;
    }


    public function nvBayes($payload)
    {
        $dataKarbo = DB::select('SELECT * FROM bahan_makanan WHERE kandungan_makanan = "karbohidrat"');
        $dataLemak = DB::select('SELECT * FROM bahan_makanan WHERE kandungan_makanan = "lemak"');
        $dataProtein = DB::select('SELECT * FROM bahan_makanan WHERE kandungan_makanan = "protein"');

        // $dataMakanan = DB::select('SELECT * FROM bahan_makanan');

        // $makanan = json_decode(json_encode($dataMakanan),true);
        $jmlKarbo = 0;
        $jmlLemak = 0;
        $jmlProtein = 0;
        $keyKarbo = -1;
        $keyProtein = -1;
        $keyLemak = -1;
        $kadungan = ['karbohidrat','lemak','protein'];
        $kadunganBagi = ['karbohidrat'=> 15 / 100,'lemak'=>65 / 100,'protein'=>35 / 100];
        $saran = ['pagi','siang','malam'];
        // foreach ($dataMakanan as $key => $value) {
        //     if ($value->kandungan_makanan == "karbohidrat") {
        //         if ($jmlKarbo + $value->karbohidrat <= $payload['karbohidrat']) {
        //             $jmlKarbo += $value->karbohidrat;
        //             $jmlLemak += $value->lemak;
        //             $jmlProtein += $value->protein;
        //             $keyKarbo++;

        //             $makanan['karbohidrat'][$keyKarbo]['id'] = $value->id;
        //             $makanan['karbohidrat'][$keyKarbo]['bahan_makanan'] = $value->bahan_makanan;
        //             $makanan['karbohidrat'][$keyKarbo]['berat'] = $value->berat;
        //             $makanan['karbohidrat'][$keyKarbo]['energi'] = $value->energi;
        //         }
        //     }

        //     if ($value->kandungan_makanan == "lemak") {
        //         if ($jmlLemak + $value->lemak <= $payload['lemak']) {
        //             $jmlKarbo += $value->karbohidrat;
        //             $jmlLemak += $value->lemak;
        //             $jmlProtein += $value->protein;
        //             $keyLemak++;

        //             $makanan['lemak'][$keyLemak]['id'] = $value->id;
        //             $makanan['lemak'][$keyLemak]['bahan_makanan'] = $value->bahan_makanan;
        //             $makanan['lemak'][$keyLemak]['berat'] = $value->berat;
        //             $makanan['lemak'][$keyLemak]['energi'] = $value->energi;
        //         }
        //     }

        //     if ($value->kandungan_makanan == "protein") {
        //         if ($jmlProtein + $value->protein <= $payload['protein']) {
        //             $jmlKarbo += $value->karbohidrat;
        //             $jmlLemak += $value->lemak;
        //             $jmlProtein += $value->protein;
        //             $keyProtein++;

        //             $makanan['protein'][$keyProtein]['id'] = $value->id;
        //             $makanan['protein'][$keyProtein]['bahan_makanan'] = $value->bahan_makanan;
        //             $makanan['protein'][$keyProtein]['berat'] = $value->berat;
        //             $makanan['protein'][$keyProtein]['energi'] = $value->energi;
        //         }
        //     }
        // }

        $hari = [];
        $hari['pagi']['data'] =  $payload['kalori'] * 20 / 100;
        $hari['pagi']['protein'] =  $payload['protein'] * 15 / 100;
        $hari['pagi']['karbohidrat'] =  $payload['karbohidrat'] * 65 / 100;
        $hari['pagi']['lemak'] =  $payload['lemak'] * 35 / 100;

        $hari['siang']['data'] = $payload['kalori'] * 30 / 100;
        $hari['siang']['protein'] =  $payload['protein'] * 15 / 100;
        $hari['siang']['karbohidrat'] =  $payload['karbohidrat'] * 65 / 100;
        $hari['siang']['lemak'] =  $payload['lemak'] * 35 / 100;

        $hari['malam']['data'] = $payload['kalori'] * 25 / 100;
        $hari['malam']['protein'] =  $payload['protein'] * 15 / 100;
        $hari['malam']['karbohidrat'] =  $payload['karbohidrat'] * 65 / 100;
        $hari['malam']['lemak'] =  $payload['lemak'] * 35 / 100;
        $arr = [];
        foreach ($kadungan as $kadunganKey => $kadunganItem) 
        {
                $keyNumber = [];
                $keyNumber['pagi'] =  0;
                $keyNumber['siang'] = 0;
                $keyNumber['malam'] = 0;
                $energi = [];
                $energi[$kadunganItem] = 0;
                $validated = [];
                foreach ($saran as $saranKey => $saranItem) 
                {
                    $bobot = $hari[$saranItem][$kadunganItem];
                    $dataMakanan = DB::table('bahan_makanan')->where('energi','<=',$bobot)
                                   ->where('kandungan_makanan',$kadunganItem)
                                   ->whereNotIn('id',$validated)
                                   ->orderBy('energi','DESC')
                                   ->groupBy('type')
                                   ->get();
                    $makanan = json_decode(json_encode($dataMakanan),true);
                    foreach ($makanan as $makananKey => $makananItem) 
                    {
                        if($makananItem['energi'] <= $bobot)
                        {
                            array_push($validated, $makananItem['id']);
                            $keyNumber[$saranItem]++;
                            $berat = $makananItem['energi'] * $kadunganBagi[$kadunganItem];
                            $arr[$saranItem][$keyNumber[$saranItem]]['makanan'] = $makananItem['bahan_makanan'];
                            $arr[$saranItem][$keyNumber[$saranItem]]['berat'] = $berat;
                            $arr[$saranItem][$keyNumber[$saranItem]]['kalori'] = $makananItem['energi'];
                            $arr[$saranItem][$keyNumber[$saranItem]]['karbohidrat'] = $makananItem['karbohidrat'];
                            $arr[$saranItem][$keyNumber[$saranItem]]['protein'] = $makananItem['protein'];
                            $arr[$saranItem][$keyNumber[$saranItem]]['lemak'] = $makananItem['lemak'];
                        }
                    }
               }
        }
        return $arr;
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
        return view('pages.pasien.naive-bayes',compact('dataRet'));
    }
}
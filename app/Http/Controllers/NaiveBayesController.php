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


    public function nvBayes($payload,$data)
    {
        $dataKarbo = DB::select('SELECT * FROM bahan_makanan WHERE kandungan_makanan = "karbohidrat"');
        $dataLemak = DB::select('SELECT * FROM bahan_makanan WHERE kandungan_makanan = "lemak"');
        $dataProtein = DB::select('SELECT * FROM bahan_makanan WHERE kandungan_makanan = "protein"');
        $jmlKarbo = 0;
        $jmlLemak = 0;
        $jmlProtein = 0;
        $keyKarbo = -1;
        $keyProtein = -1;
        $keyLemak = -1;
        $kadungan = ['karbohidrat','lemak','protein'];
        $kadunganBagi = ['karbohidrat'=> 60 / 100,'lemak'=>20 / 100,'protein'=>10 / 100];
        $saran = ['pagi','siang','malam'];

        $dataBagi = [];

        $hari = [];
        $hari['pagi']['data'] =  $payload['kalori'] * 30 / 100;
        $hari['pagi']['protein'] =  $payload['protein'] * 10 / 100;
        $hari['pagi']['karbohidrat'] =  $payload['karbohidrat'] * 60 / 100;
        $hari['pagi']['lemak'] =  $payload['lemak'] * 20 / 100;
        $dataBagi['pagi'] = $hari['pagi']['data'] + $hari['pagi']['protein'] + $hari['pagi']['karbohidrat'] + $hari['pagi']['lemak'];

        $hari['siang']['data'] = $payload['kalori'] * 40 / 100;
        $hari['siang']['protein'] =  $payload['protein'] * 10 / 100;
        $hari['siang']['karbohidrat'] =  $payload['karbohidrat'] * 60 / 100;
        $hari['siang']['lemak'] =  $payload['lemak'] * 20 / 100;
        $dataBagi['siang'] = $hari['siang']['data'] + $hari['siang']['protein'] + $hari['siang']['karbohidrat'] + $hari['siang']['lemak'];

        $hari['malam']['data'] = $payload['kalori'] * 30 / 100;
        $hari['malam']['protein'] =  $payload['protein'] * 10 / 100;
        $hari['malam']['karbohidrat'] =  $payload['karbohidrat'] * 60 / 100;
        $hari['malam']['lemak'] =  $payload['lemak'] * 20 / 100;
        $dataBagi['malam'] = $hari['malam']['data'] + $hari['malam']['protein'] + $hari['malam']['karbohidrat'] + $hari['malam']['lemak'];
        
        $arr = [];
        $arrId = [];
        $makananArr = [];
        $validated = [];
        $bobotVal = 0;
        foreach ($saran as $saranKey => $saranItem) 
        {
                $keyNumber = [];
                $keyNumber['pagi'] =  0;
                $keyNumber['siang'] = 0;
                $keyNumber['malam'] = 0;
                
                foreach ($kadungan as $kadunganKey => $kadunganItem) 
                {
                    $bobot = $dataBagi[$saranItem];
                    if($kadunganItem == 'karbohidrat')
                    {
                        $dataMakanan = DB::table('bahan_makanan')
                                   ->where('kandungan_makanan',$kadunganItem)
                                   ->where('energi','<=',$bobot)
                                   ->orderBy('energi','DESC')
                                   ->whereNotIn('id',$validated)
                                   ->limit(1)
                                   ->get();
                    }else
                    {
                        $dataMakanan = DB::table('bahan_makanan')
                                   ->where('kandungan_makanan',$kadunganItem)
                                   ->where('energi','<=',$bobot)
                                   ->orderBy('energi','DESC')
                                   ->whereNotIn('id',$validated)
                                   ->limit(2)
                                   ->get();
                    }

                    $makanan = json_decode(json_encode($dataMakanan),true);
                    foreach ($makanan as $makananKey => $makananItem) 
                    {
                       $bobotVal += $makananItem['energi']; 
                       if($bobotVal <= $payload['kalori'])
                       {
                            array_push($validated, $makananItem['id']);
                            array_push($arrId, $makananItem['id']);
                            array_push($makananArr, $makananItem['bahan_makanan']);
                            $keyNumber[$saranItem]++;
                            $number = $keyNumber[$saranItem];
                            $berat = $makananItem['energi'] * $kadunganBagi[$kadunganItem];
                            $arr[$saranItem][$number]['makanan'] = $makananItem['bahan_makanan'];
                            $arr[$saranItem][$number]['berat'] = $berat;
                            $arr[$saranItem][$number]['kalori'] = $makananItem['energi'];
                            $arr[$saranItem][$number]['karbohidrat'] = $makananItem['karbohidrat'];
                            $arr[$saranItem][$number]['protein'] = $makananItem['protein'];
                            $arr[$saranItem][$number]['lemak'] = $makananItem['lemak'];
                            $arr[$saranItem][$number]['kandungan_makanan'] = $makananItem['kandungan_makanan'];

                        }
                    }
               }
        }
        $result = [];
        $result['arr'] = $arr;
        return $result;
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

    public function getSaran(Request $request)
    {
        $dataRet = [];
        if(count($request->all()) > 0)
        {
            $qry = DB::table('saran_makanan as sm');
                $qry->join('kebutuhan_gizi as kgz','kgz.id','=','sm.kebutuhan_gizi_id');
                $qry->join('pasien as ps','ps.id','=','kgz.user_id');
                $qry->select('sm.*','kgz.user_id','kgz.kode as kode_kebutuhan','kgz.umur','kgz.tinggi','kgz.berat','kgz.stress_fac'
                    ,'kgz.activity_fac','kgz.kalori','kgz.protein','kgz.lemak','kgz.karbohidrat');
            if($request->kode != null)
            {
                $qry->where('ps.kode',$request->kode);
                $qry->Orwhere('kgz.kode',$request->kode);
            }
            $qry->groupBy('kgz.id');
            $data = $qry->get();
            $dataRet = json_decode(json_encode($data),true);

            foreach ($dataRet as $key => $value) 
            {
                $dataRet[$key]['data'] = json_decode($dataRet[$key]['data'],true);
                $pasien = DB::table('pasien')->where('id',$value['user_id'])->first();
                $dataRet[$key]['nama_pasien'] = '';
                $dataRet[$key]['jenis_kelamin'] = '';
                $dataRet[$key]['kode_pasien'] = '';
                if($pasien)
                {
                    $dataRet[$key]['nama_pasien'] = $pasien->nama;
                    $dataRet[$key]['jenis_kelamin'] = $pasien->jenis_kelamin;
                    $dataRet[$key]['kode_pasien'] = $pasien->kode;
                }
                $dataRet[$key]['created_at'] = Carbon::parse($dataRet[$key]['created_at'])->format('Y F d H:i:s');
            }
        }
        return view('pages.pasien.naive-bayes',compact('dataRet','request'));
    }
}
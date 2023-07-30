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

        //Pengemplokan Data Pasien berdasarkan Kategori Waktu
        $hari = [];
        $hari['pagi']['data'] =  $payload['kalori'] * 30 / 100;
        $dataBagi['pagi'] = $hari['pagi']['data'];

        $hari['siang']['data'] = $payload['kalori'] * 40 / 100;
        $dataBagi['siang'] = $hari['siang']['data'];

        $hari['malam']['data'] = $payload['kalori'] * 30 / 100;
        $dataBagi['malam'] = $hari['malam']['data'];
        
        $kadunganBagi = ['karbohidrat'=> 60 / 100,'lemak'=>20 / 100,'protein'=>10 / 100];

        //Pengelompokan data kebutuhan makanan berdasarkan kategori waktu
        $dataMakananPagi = DB::table('bahan_makanan')->where('type','pagi')->get();
        $dataMakananSiang = DB::table('bahan_makanan')->where('type','siang')->get();
        $dataMakananMalam = DB::table('bahan_makanan')->where('type','malam')->get();

        //total data
        $totaldataMakananPagi = count($dataMakananPagi);
        $totaldataMakananSiang = count($dataMakananSiang);
        $totaldataMakananMalam = count($dataMakananMalam);

        //Probabilitas Fitur

        //mean
        $dataMakananPagiSum = DB::table('bahan_makanan')->where('type','pagi')->sum('energi') / $totaldataMakananPagi;
        $dataMakananSiangSum = DB::table('bahan_makanan')->where('type','siang')->sum('energi') / $totaldataMakananSiang;
        $dataMakananMalamSum = DB::table('bahan_makanan')->where('type','malam')->sum('energi') / $totaldataMakananMalam;

        $mean = [];
        $mean['pagi'] = $dataMakananPagiSum;
        $mean['siang'] = $dataMakananSiangSum;
        $mean['malam'] = $dataMakananMalamSum;
        //end of mean

        //standart deviasi
        $std = [];
        $std['pagi'] = 0;
        $std['siang'] = 0;
        $std['malam'] = 0;

        foreach ($dataMakananPagi as $key => $value) 
        {
            $std['pagi'] += ($value->energi-$mean['pagi']) * ($value->energi-$mean['pagi']);
        }
        $std['pagi'] = sqrt($std['pagi'] / $totaldataMakananPagi);

        foreach ($dataMakananSiang as $key => $value) 
        {
            $std['siang'] += ($value->energi-$mean['siang']) * ($value->energi-$mean['siang']);
        }
        $std['siang'] = sqrt($std['siang'] / $totaldataMakananSiang);

        foreach ($dataMakananMalam as $key => $value) 
        {
            $std['malam'] += ($value->energi-$mean['malam']) * ($value->energi-$mean['malam']);
        }
        $std['malam'] = sqrt($std['malam'] / $totaldataMakananMalam);
        //end of deviasi

        //Probabilitas Posterior 
        $pi = sqrt(2 * 3.14); // pi probobilitas
        //convert to array of object

        $dataMakananPagi = DB::table('bahan_makanan')->where('type','pagi')->get();
        $dataMakananPagi = json_decode(json_encode($dataMakananPagi),true);
        $dataMakananSiang = DB::table('bahan_makanan')->where('type','siang')->get();
        $dataMakananSiang = json_decode(json_encode($dataMakananSiang),true);
        $dataMakananMalam = DB::table('bahan_makanan')->where('type','malam')->get();
        $dataMakananMalam = json_decode(json_encode($dataMakananMalam),true);

        //hitung probabilitas posterior
        foreach ($dataMakananPagi as $key => $value) 
        {
            $kali = (($value['energi'] - $mean['pagi']) / $std['pagi']) * (($value['energi'] - $mean['pagi']) / $std['pagi']);
            $probi = 1 / ($std['pagi'] * $pi) * exp(-0.5 * $kali);
            $dataMakananPagi[$key]['prob'] = $probi;
        }

        foreach ($dataMakananSiang as $key => $value) 
        {
            $kali = (($value['energi'] - $mean['siang']) / $std['siang']) * (($value['energi'] - $mean['siang']) / $std['siang']);
            $probi = 1 / ($std['siang'] * $pi) * exp(-0.5 * $kali);
            $dataMakananSiang[$key]['prob'] = $probi;
        }

        foreach ($dataMakananMalam as $key => $value) 
        {
            $kali = (($value['energi'] - $mean['malam']) / $std['malam']) * (($value['energi'] - $mean['malam']) / $std['malam']);
            $probi = 1 / ($std['malam'] * $pi) * exp(-0.5 * $kali);
            $dataMakananMalam[$key]['prob'] = $probi;
        }
        
        //prankingan
        $dataMakananPagi = $this->array_sort_by_column_desc($dataMakananPagi,'prob');
        $dataMakananSiang = $this->array_sort_by_column_desc($dataMakananSiang,'prob');
        $dataMakananMalam = $this->array_sort_by_column_desc($dataMakananMalam,'prob');

        $arr = [];
        $arr['pagi'] = [];
        $arr['siang'] = [];
        $arr['malam'] = [];
        //pengkalisifikan
        //pagi
        $tempPagiKarbo = [];
        foreach ($dataMakananPagi as $key => $makananItem) 
        {
            if($makananItem['kandungan_makanan'] == 'karbohidrat')
            {
                $berat = $dataMakananPagi[$key]['energi'] * $kadunganBagi[$dataMakananPagi[$key]['kandungan_makanan']];
                $tempPagiKarbo['makanan'] = $dataMakananPagi[$key]['bahan_makanan'];
                $tempPagiKarbo['berat'] = $berat;
                $tempPagiKarbo['kalori'] = $dataMakananPagi[$key]['energi'];
                $tempPagiKarbo['karbohidrat'] = $dataMakananPagi[$key]['karbohidrat'];
                $tempPagiKarbo['protein'] = $dataMakananPagi[$key]['protein'];
                $tempPagiKarbo['lemak'] = $dataMakananPagi[$key]['lemak'];
                $tempPagiKarbo['kandungan_makanan'] = $dataMakananPagi[$key]['kandungan_makanan'];
                $tempPagiKarbo['prob'] = $dataMakananPagi[$key]['prob'];
                break;
            }
        }
        $arr['pagi'][0] = $tempPagiKarbo;
        $numberPagi = 1;
        foreach ($dataMakananPagi as $key => $value) 
        {
            if($numberPagi <= 3)
            {
                if($value['kandungan_makanan'] != 'karbohidrat')
                {
                    $berat = $dataMakananPagi[$key]['energi'] * $kadunganBagi[$dataMakananPagi[$key]['kandungan_makanan']];
                    $arr['pagi'][$numberPagi]['makanan'] = $dataMakananPagi[$key]['bahan_makanan'];
                    $arr['pagi'][$numberPagi]['berat'] = $berat;
                    $arr['pagi'][$numberPagi]['kalori'] = $dataMakananPagi[$key]['energi'];
                    $arr['pagi'][$numberPagi]['karbohidrat'] = $dataMakananPagi[$key]['karbohidrat'];
                    $arr['pagi'][$numberPagi]['protein'] = $dataMakananPagi[$key]['protein'];
                    $arr['pagi'][$numberPagi]['lemak'] = $dataMakananPagi[$key]['lemak'];
                    $arr['pagi'][$numberPagi]['kandungan_makanan'] = $dataMakananPagi[$key]['kandungan_makanan'];
                    $arr['pagi'][$numberPagi]['prob'] = $dataMakananPagi[$key]['prob'];
                    $numberPagi++;
                }
            }
        }
        //siang
        $tempSiangKarbo = [];
        foreach ($dataMakananSiang as $key => $makananItem) 
        {
            if($makananItem['kandungan_makanan'] == 'karbohidrat')
            {
                $berat = $dataMakananSiang[$key]['energi'] * $kadunganBagi[$dataMakananSiang[$key]['kandungan_makanan']];
                $tempSiangKarbo['makanan'] = $dataMakananSiang[$key]['bahan_makanan'];
                $tempSiangKarbo['berat'] = $berat;
                $tempSiangKarbo['kalori'] = $dataMakananSiang[$key]['energi'];
                $tempSiangKarbo['karbohidrat'] = $dataMakananSiang[$key]['karbohidrat'];
                $tempSiangKarbo['protein'] = $dataMakananSiang[$key]['protein'];
                $tempSiangKarbo['lemak'] = $dataMakananSiang[$key]['lemak'];
                $tempSiangKarbo['kandungan_makanan'] = $dataMakananSiang[$key]['kandungan_makanan'];
                $tempSiangKarbo['prob'] = $dataMakananSiang[$key]['prob'];
                break;
            }
        }
        $arr['siang'][0] = $tempSiangKarbo;
        $numberSiang = 1;
        foreach ($dataMakananSiang as $key => $value) 
        {
            if($numberSiang <= 3)
            {
                if($value['kandungan_makanan'] != 'karbohidrat' && $makananItem['bahan_makanan'])
                {
                    $berat = $dataMakananSiang[$key]['energi'] * $kadunganBagi[$dataMakananSiang[$key]['kandungan_makanan']];
                    $arr['siang'][$numberSiang]['makanan'] = $dataMakananSiang[$key]['bahan_makanan'];
                    $arr['siang'][$numberSiang]['berat'] = $berat;
                    $arr['siang'][$numberSiang]['kalori'] = $dataMakananSiang[$key]['energi'];
                    $arr['siang'][$numberSiang]['karbohidrat'] = $dataMakananSiang[$key]['karbohidrat'];
                    $arr['siang'][$numberSiang]['protein'] = $dataMakananSiang[$key]['protein'];
                    $arr['siang'][$numberSiang]['lemak'] = $dataMakananSiang[$key]['lemak'];
                    $arr['siang'][$numberSiang]['kandungan_makanan'] = $dataMakananSiang[$key]['kandungan_makanan'];
                    $arr['siang'][$numberSiang]['prob'] = $dataMakananSiang[$key]['prob'];
                    $numberSiang++;
                }
            }
        }
        //malam
        $tempMalamKarbo = [];
        foreach ($dataMakananMalam as $key => $makananItem) 
        {
            if($makananItem['kandungan_makanan'] == 'karbohidrat')
            {
               //$tempMalamKarbo = $makananItem;
                $berat = $dataMakananMalam[$key]['energi'] * $kadunganBagi[$dataMakananMalam[$key]['kandungan_makanan']];
                $tempMalamKarbo['makanan'] = $dataMakananMalam[$key]['bahan_makanan'];
                $tempMalamKarbo['berat'] = $berat;
                $tempMalamKarbo['kalori'] = $dataMakananMalam[$key]['energi'];
                $tempMalamKarbo['karbohidrat'] = $dataMakananMalam[$key]['karbohidrat'];
                $tempMalamKarbo['protein'] = $dataMakananMalam[$key]['protein'];
                $tempMalamKarbo['lemak'] = $dataMakananMalam[$key]['lemak'];
                $tempMalamKarbo['kandungan_makanan'] = $dataMakananMalam[$key]['kandungan_makanan'];
                $tempMalamKarbo['prob'] = $dataMakananMalam[$key]['prob'];
                break;
            }
        }
        $arr['malam'][0] = $tempMalamKarbo;
        $numberMalam = 1;
        foreach ($dataMakananMalam as $key => $value) 
        {
            if($numberMalam <= 3)
            {
                if($value['kandungan_makanan'] != 'karbohidrat' && $makananItem['bahan_makanan'])
                {
                    $berat = $dataMakananMalam[$key]['energi'] * $kadunganBagi[$dataMakananMalam[$key]['kandungan_makanan']];
                    $arr['malam'][$numberMalam]['makanan'] = $dataMakananMalam[$key]['bahan_makanan'];
                    $arr['malam'][$numberMalam]['berat'] = $berat;
                    $arr['malam'][$numberMalam]['kalori'] = $dataMakananMalam[$key]['energi'];
                    $arr['malam'][$numberMalam]['karbohidrat'] = $dataMakananMalam[$key]['karbohidrat'];
                    $arr['malam'][$numberMalam]['protein'] = $dataMakananMalam[$key]['protein'];
                    $arr['malam'][$numberMalam]['lemak'] = $dataMakananMalam[$key]['lemak'];
                    $arr['malam'][$numberMalam]['kandungan_makanan'] = $dataMakananMalam[$key]['kandungan_makanan'];
                    $arr['malam'][$numberMalam]['prob'] = $dataMakananMalam[$key]['prob'];
                    $numberMalam++;
                }
            }
        }
        //dd($arr['malam']);
        $result = [];
        $result['arr'] = $arr;
        return $result;
    }

    function checkValueIsAvail($arr,$index,$value)
    {
        $ada = false;
        foreach ($arr as $key => $value) 
        {
            if(isset($value[$index]))
            {
                if($value[$index] == $value)
                {
                    $ada = true;
                    break;
                }
            }
        }
        return $ada;
    }

    function array_sort_by_column_desc(&$arr, $col, $dir = SORT_DESC) 
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
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
            //dd($dataRet);
            foreach ($dataRet as $key => $value) 
            {
                $dataRet[$key]['data'] = json_decode($dataRet[$key]['data'],true);
                //dd($dataRet[$key]['data']);
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
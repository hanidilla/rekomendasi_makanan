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
        foreach ($data as $key => $value) {
            // array_push($kategori, [
            //     $value->kandungan_makanan => number_format($value->jumlah / count($length), 2, '.', '')
            // ])
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
                    ["protein" =>  $value->protein, "lemak" => $value->lemak, "karbohidrat" => $value->karbohidrat];
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
                    ["protein" => pow(((float)$value->protein - $dataMean[$value->kandungan_makanan]["protein"]), 2), "lemak" => pow(((float)$value->lemak - $dataMean[$value->kandungan_makanan]["lemak"]), 2), "karbohidrat" => pow(((float)$value->karbohidrat - $dataMean[$value->kandungan_makanan]["karbohidrat"]), 2)];
            } else if (in_array($value->kandungan_makanan, $bahan)) {
                switch ($value->kandungan_makanan) {
                    case 'protein':
                        # code...
                        $dataVariance["protein"]["protein"] += pow(((float)$value->protein - $dataMean["protein"]["protein"]), 2);
                        $dataVariance["protein"]["karbohidrat"] += pow(((float)$value->karbohidrat - $dataMean["protein"]["karbohidrat"]), 2);
                        $dataVariance["protein"]["lemak"] += pow(((float)$value->lemak - $dataMean["protein"]["lemak"]), 2);
                        break;
                    case 'lemak':

                        $dataVariance["lemak"]["protein"] += pow(((float)$value->protein - $dataMean["lemak"]["protein"]), 2);
                        $dataVariance["lemak"]["karbohidrat"] += pow(((float)$value->karbohidrat - $dataMean["lemak"]["karbohidrat"]), 2);
                        $dataVariance["lemak"]["lemak"] += pow(((float)$value->lemak - $dataMean["lemak"]["lemak"]), 2);
                        break;
                    case 'karbohidrat':
                        # code...
                        $dataVariance["karbohidrat"]["protein"] += pow(((float)$value->protein - $dataMean["karbohidrat"]["protein"]), 2);
                        $dataVariance["karbohidrat"]["karbohidrat"] += pow(((float)$value->karbohidrat - $dataMean["karbohidrat"]["karbohidrat"]), 2);
                        $dataVariance["karbohidrat"]["lemak"] += pow(((float)$value->lemak - $dataMean["karbohidrat"]["lemak"]), 2);
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
            $dataNormal[$k] =  ["protein" =>  $this->distNorm($protein, $v["protein"], $stdev[$k]["protein"]), "lemak" =>  $this->distNorm($lemak, $v["lemak"], $stdev[$k]["lemak"]), "karbohidrat" =>  $this->distNorm($karbo, $v["karbohidrat"], $stdev[$k]["karbohidrat"])];
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
            $res[$key] =  $value["protein"] * $value["lemak"] * $value["karbohidrat"] * (float)$prob[$key];
        }
        return $res;
    }


    public function nvBayes($payload)
    {
        $dataKarbo = DB::select('SELECT * FROM bahan_makanan WHERE kandungan_makanan = "karbohidrat"');
        $dataLemak = DB::select('SELECT * FROM bahan_makanan WHERE kandungan_makanan = "lemak"');
        $dataProtein = DB::select('SELECT * FROM bahan_makanan WHERE kandungan_makanan = "protein"');

        $dataMakanan = DB::select('SELECT * FROM bahan_makanan');
        // dd(array_column($dataMakanan, 'protein'));
        $saran = [];
        $makanan = [];
        $jmlKarbo = 0;
        $jmlLemak = 0;
        $jmlProtein = 0;
        foreach ($dataMakanan as $key => $value) {
            if ($value->kandungan_makanan == "karbohidrat") {
                if ($jmlKarbo + $value->karbohidrat <= $payload['karbohidrat']) {
                    array_push($makanan, $value->id);
                    $jmlKarbo += $value->karbohidrat;
                }
            }

            if ($value->kandungan_makanan == "lemak") {
                if ($jmlLemak + $value->lemak  <= $payload['lemak']) {
                    array_push($makanan, $value->id);
                    $jmlLemak += $value->lemak;
                }
            }

            if ($value->kandungan_makanan == "protein") {
                if ($jmlProtein + $value->protein <= $payload['protein']) {
                    array_push($makanan, $value->id);
                    $jmlProtein += $value->protein;
                }
            }
        }

        $saran = [
            "karbohidrat" => $jmlKarbo,
            "lemak" => $jmlLemak,
            "protein" => $jmlProtein
        ];
        // dd($jmlKarbo, $jmlLemak, $jmlProtein);
        return $makanan;
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
        $data = SaranMakanan::with('gizi.pasien')->get();
        $dataRet = [];
        $listId = [];
        // dd($data);
        foreach ($data as $key => $value) {
            array_push(
                $dataRet,
                [
                    "pasien" => KebutuhanGizi::where("id", $value->kebutuhan_gizi_id)->first(),
                    "saran_makanan" => []
                ]
            );

            foreach (json_decode($value["saran_makanan"]) as $k => $v2) {

                // if ($dataRet == null || !in_array($v2, $listId)) {
                # code...
                // =

                array_push($dataRet[$key]["saran_makanan"], BahanMakanan::find($v2));
                // }
            }
        }
        // dd($dataRet);

        return $this->success($dataRet, "");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BahanMakanan;
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
            // $data = DB::select('SELECT kandungan_makanan, SUM("karbohidrat") as karbohidrat, SUM("protein") as protein, SUM("lemak") as lemak  FROM bahan_makanan GROUP BY ');
            // dd($data);

            // dd($kategori);

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

    public function normalDist($karbo = 1, $protein = 1, $lemak = 1)
    {
        $mean = $this->mean();
        $stdev = $this->deviasi();
        $dataNormal = [];
        // dd(\stats_dens_normal);
        foreach ($mean as $k => $v) {
            $normal = new Continuous\Normal($v["protein"], $stdev[$k]["protein"]);
            $dataNormal[$k] =  ["protein" =>  $normal->pdf($protein), "lemak" =>  $normal->pdf($lemak), "karbohidrat" =>  $normal->pdf($karbo)];
        }
        // dd($dataNormal);
        // return $this->success($dataNormal, "Sukses");
        return $dataNormal;
    }

    public function naiveBayes()
    {
        $prob = $this->probability();
        // dd($prob);
        $dataNorm = $this->normalDist();
        $res = [];
        foreach ($dataNorm as $key => $value) {
            // dd($value["protein"] * $value["lemak"] * $value["karbohidrat"]);
            $res[$key] =  $value["protein"] * $value["lemak"] * $value["karbohidrat"] * (float)$prob[$key];
        }
        return $res;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\FaktorAktivitas;
use App\Models\FaktorStress;
use App\Models\KoreksiUmur;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function storeAktivitas(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = FaktorAktivitas::create($request->all());
            DB::commit();
            return $this->success($data, "");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $this->error($th->getMessage());
        }
    }

    public function getAktivitas()
    {
        $data = FaktorAktivitas::all();
        return $this->success($data, "Berhasil Didapat");
    }

    public function getAktivitasById($id)
    {
        $data = FaktorAktivitas::find($id);
        return $this->success($data, "Berhasil Didapat");
    }

    public function updateAktivitas(Request $request, $id)
    {
        try {
            //code...
            DB::beginTransaction();
            $data = FaktorAktivitas::find($id)->update($request->all());
            // dd($request->all());
            DB::commit();
            return $this->success($data, "");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return  $this->error($th->getMessage());
        }
    }

    public function deleteAktivitas($id)
    {
        try {
            //code...
            DB::beginTransaction();
            $data = FaktorAktivitas::find($id)->delete();
            DB::commit();
            return $this->success($data, "");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return  $this->error($th->getMessage());
        }
    }

    public function storeStress(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = FaktorStress::create($request->all());
            DB::commit();
            return $this->success($data, "");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $this->error($th->getMessage());
        }
    }

    public function getStress()
    {
        $data = FaktorStress::all();
        return $this->success($data, "Berhasil Didapat");
    }

    public function getStressById($id)
    {
        $data = FaktorStress::find($id);
        return $this->success($data, "Berhasil Didapat");
    }

    public function updateStress(Request $request, $id)
    {
        try {
            //code...
            DB::beginTransaction();
            $data = FaktorStress::find($id)->update($request->all());
            DB::commit();
            return $this->success($data, "");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return  $this->error($th->getMessage());
        }
    }

    public function deleteStress($id)
    {
        try {
            //code...
            DB::beginTransaction();
            $data = FaktorStress::find($id)->delete();
            DB::commit();
            return $this->success($data, "");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return  $this->error($th->getMessage());
        }
    }
}

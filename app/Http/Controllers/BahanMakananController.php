<?php

namespace App\Http\Controllers;

use App\Models\BahanMakanan;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BahanMakananController extends Controller
{
    //
    use Response;

    public function index()
    {
        try {

            $data = BahanMakanan::all();

            return $this->success($data, 'Data Makanan Berhasil Diambil');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->error($th->getMessage(), $th->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = BahanMakanan::create($request->all());

            DB::commit();
            return $this->success($data, "Data berhasil Disimpan");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $this->error($th->getMessage(), $th->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = BahanMakanan::find($id)->update(
                $request->all()
            );
            DB::commit();
            return $this->success($data, "Data berhasil diupdate");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $this->error($th->getMessage(), $th->getCode());
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $data = BahanMakanan::find($id)->delete();
            DB::commit();
            return $this->success($data, "Data berhasil dihapus");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $this->error($th->getMessage(), $th->getCode());
        }
    }
}
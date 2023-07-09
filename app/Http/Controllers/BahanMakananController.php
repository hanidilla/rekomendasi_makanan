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

    public function show($id)
    {
        try {
            $data = BahanMakanan::find($id);
            return $this->success($data, "Data berhasil diambil");
        } catch (\Throwable $th) {
            //throw $th;
            return $this->error($th->getMessage(), $th->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($request->protein > $request->lemak && $request->protein > $request->karbohidrat) {
                $request['kandungan_makanan'] = 'protein';
                # code...
            } else if ($request->lemak > $request->protein && $request->lemak > $request->karbohidrat) {
                $request['kandungan_makanan'] = 'lemak';
            } else if ($request->karbohidrat > $request->lemak && $request->karbohidrat > $request->protein) {
                $request['kandungan_makanan'] = 'karbohidrat';
            }
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

    public function destroy($id)
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

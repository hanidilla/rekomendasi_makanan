<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Traits\Response;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    
    //
    use Response;
    public function index()
    {
        $data = Pasien::all();
        return $this->success($data, 'Berhasil Mendapatkan Semua Pasien');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $pasien = Pasien::create($data);
        return $this->success($pasien, 'Pasien Berhasil Dibuat');
    }

    public function show($id)
    {
        $pasien = Pasien::find($id);
        if (!$pasien) {
            return $this->error('Pasien Tidak Ditemukan', 404);
        }
        return $this->success($pasien, 'Pasien Berhasil Ditemukan');
    }

    public function update(Request $request, $id)
    {
        $pasien = Pasien::find($id);
        if (!$pasien) {
            return $this->error('Pasien Tidak Ditemukan', 404);
        }
        $data = $request->all();
        $pasien->fill($data);
        $pasien->save();
        return $this->success($pasien, 'Pasien Berhasil Diupdate');
    }

    public function destroy($id)
    {
        $pasien = Pasien::find($id);
        if (!$pasien) {
            return $this->error('Pasien Tidak Ditemukan', 404);
        }
        $pasien->delete();
        return $this->success($pasien, 'Pasien Berhasil Dihapus');
    }
}

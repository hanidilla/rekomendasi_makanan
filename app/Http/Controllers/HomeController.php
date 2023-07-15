<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::check())
        {
            return view('pages.admin.makanan');
        }

        return redirect('/');
    }

    public function web(Request $request)
    {
        $ada = null;
        if($request->kode_pasien != null)
        {
            //$pasien = DB::table('pasien')->where('kode',$request->kode_pasien)->first();
            
            $qry = DB::table('saran_makanan as sm');
                $qry->join('kebutuhan_gizi as kgz','kgz.id','=','sm.kebutuhan_gizi_id');
                $qry->join('pasien as ps','ps.id','=','kgz.user_id');
                $qry->select('sm.*','kgz.user_id','kgz.kode as kode_kebutuhan','kgz.umur','kgz.tinggi','kgz.berat','kgz.stress_fac'
                    ,'kgz.activity_fac','kgz.kalori','kgz.protein','kgz.lemak','kgz.karbohidrat');
            if($request->kode_pasien != null)
            {
                $qry->where('ps.kode',$request->kode_pasien);
                $qry->Orwhere('kgz.kode',$request->kode_pasien);
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
            //dd($dataRet);
            if(count($dataRet) <= 0)
            {   
                $ada = 'Mohon maaf kode pasien atau kode kebutuhan tidak ditemukan atau data anda belum dihitung';
                return view('pages.web.home',compact('ada','request'));
            }
            //dd($dataRet);
            return view('pages.web.result',compact('dataRet','request'));
        }
        return view('pages.web.home',compact('ada','request'));
    }
}

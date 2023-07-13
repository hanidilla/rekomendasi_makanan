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
            
            $data = DB::table('saran_makanan as sm')
                ->join('kebutuhan_gizi as kgz','kgz.id','=','sm.kebutuhan_gizi_id')
                ->select('sm.*','kgz.user_id','umur','tinggi','berat','stress_fac','activity_fac','kalori','protein','lemak','karbohidrat')
                ->get();
            $dataRet = json_decode(json_encode($data),true);
            foreach ($dataRet as $key => $value) 
            {
                $dataRet[$key]['data'] = json_decode($dataRet[$key]['data'],true);
                $pasien = DB::table('pasien')->where('kode',$request->kode_pasien)->first();
                if($pasien)
                {
                     $checkYa = DB::table('saran_makanan as sm')
                        ->join('kebutuhan_gizi as kgz','kgz.id','=','sm.kebutuhan_gizi_id')
                        ->join('pasien as us','us.id','=','kgz.user_id')
                        ->select('sm.*','kgz.user_id','kgz.umur','kgz.tinggi','kgz.berat','kgz.stress_fac','kgz.activity_fac','kgz.kalori'
                            ,'kgz.protein','kgz.lemak','kgz.karbohidrat')
                        ->where('kgz.user_id',$pasien->id)
                        ->get();

                    if($checkYa->isEmpty())
                    {
                        unset($dataRet[$key]);
                    }else
                    {
                        $dataRet[$key]['nama_pasien'] = '';
                        $dataRet[$key]['jenis_kelamin'] = '';
                        $dataRet[$key]['nama_pasien'] = $pasien->nama;
                        $dataRet[$key]['jenis_kelamin'] = $pasien->jenis_kelamin;
                        $dataRet[$key]['created_at'] = Carbon::parse($dataRet[$key]['created_at'])->format('Y F d H:i:s');
                    }
                }else
                {
                    unset($dataRet[$key]);
                }
                
            }
            //dd($dataRet);
            if(count($dataRet) <= 0)
            {   
                $ada = 'Mohon maaf kode pasien tidak ditemukan atau data anda belum dihitung';
                return view('pages.web.home',compact('ada'));
            }
            //dd($dataRet);
            return view('pages.web.result',compact('dataRet'));
        }
        return view('pages.web.home',compact('ada'));
    }
}

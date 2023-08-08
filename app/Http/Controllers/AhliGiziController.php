<?php

namespace App\Http\Controllers;

use App\Models\BahanMakanan;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AhliGiziController extends Controller
{
	public function index()
	{
		$data = DB::table('users')->where('role','ahli_gizi')->get();
		return view('pages.admin.ahli_gizi',compact('data'));
	}

	public function store(Request $request)
	{
		$check = DB::table('users')->where('email',$request->email)->first();
		if($check)
		{
			return redirect()->back()->with('error','Data ahli gizi email '.$request->email.' sudah digunakan');
		}
		$createdAt = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
		DB::table('users')->insert([
			'name'=>$request->name,
			'role'=>'ahli_gizi',
			'email'=>$request->email,
			'password'=>bcrypt($request->password),
			'created_at'=>$createdAt
		]);

		return redirect()->back()->with('success','Data ahli gizi berhasil ditambahkan');
	}

	public function update(Request $request,$id)
	{
		$check = DB::table('users')->where('email',$request->email)->first();
		if($check)
		{
			if($check->id != $id)
			{
				return redirect()->back()->with('error','Data ahli gizi email '.$request->email.' sudah digunakan');
			}

			$password = $request->password;
			if($password == null)
			{
				$password = bcrypt($check->password);
			}
		}
		$createdAt = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
		DB::table('users')->where('id',$id)->update([
			'name'=>$request->name,
			'role'=>'ahli_gizi',
			'email'=>$request->email,
			'password'=>$password,
			'updated_at'=>$createdAt
		]);
		return redirect()->back()->with('success','Data ahli gizi berhasil diubah');
	}

	public function delete($id)
	{
		DB::table('users')->where('id',$id)->delete();
		return redirect()->back()->with('success','Data ahli gizi berhasil dihapus');
	}
}
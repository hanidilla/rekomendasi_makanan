<?php

namespace App\Http\Controllers;

use App\Models\BahanMakanan;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestingController extends Controller
{
	public function test()
	{
		$password = bcrypt('admin@rkmdm.my.id');
		return $password;
	}
}
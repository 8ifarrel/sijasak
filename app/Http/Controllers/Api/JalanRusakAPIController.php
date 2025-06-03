<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JalanRusak;

class JalanRusakAPIController extends Controller
{
	public function index()
	{
		return response()->json(
			JalanRusak::select(
				'id',
				'deskripsi',
				'longitude',
				'latitude',
				'tingkat_keparahan',
				'foto',
				'sudah_diperbaiki',
				'created_at',
			)->get()
		);
	}
}

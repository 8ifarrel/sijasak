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
			JalanRusak::with('foto:id,jalan_rusak_id,foto')
				->select(
					'id',
					'deskripsi',
					'longitude',
					'latitude',
					'tingkat_keparahan',
					'sudah_diperbaiki',
					'created_at',
				)
				->get()
				->map(function ($item) {
					return [
						'id' => $item->id,
						'deskripsi' => $item->deskripsi,
						'longitude' => $item->longitude,
						'latitude' => $item->latitude,
						'tingkat_keparahan' => $item->tingkat_keparahan,
						'sudah_diperbaiki' => $item->sudah_diperbaiki,
						'created_at' => $item->created_at,
						'foto' => $item->foto->pluck('foto')->map(function ($f) {
							return asset('storage/' . $f);
						})->values(),
					];
				})
		);
	}
}

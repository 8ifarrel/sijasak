<?php

namespace App\Http\Controllers\guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JalanRusak;

class BerandaGuestController extends Controller
{
    public function index()
    {
        $page_title = 'Beranda';
        $meta_description = 'Meta description halaman beranda';

        return view('guest.pages.beranda.index', [
            'page_title' => $page_title,
            'meta_description' => $meta_description,
        ]);
    }

    public function jalanRusakApi()
    {
        return response()->json(
            JalanRusak::select('id', 'deskripsi', 'longitude', 'latitude', 'tingkat_keparahan', 'foto', 'created_at')->get()
        );
    }
}

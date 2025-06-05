<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JalanRusak;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $page_title = 'Dashboard';
        $meta_description = 'Meta description halaman dashboard';

        // Statistik jalan rusak
        $total_jalan_rusak = JalanRusak::where('sudah_diperbaiki', false)->count();
        $total_jalan_diperbaiki = JalanRusak::where('sudah_diperbaiki', true)->count();

        return view('admin.pages.dashboard.index', [
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'total_jalan_rusak' => $total_jalan_rusak,
            'total_jalan_diperbaiki' => $total_jalan_diperbaiki,
        ]);
    }
}

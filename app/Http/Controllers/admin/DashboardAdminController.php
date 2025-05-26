<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $page_title = 'Dashboard';
        $meta_description = 'Meta description halaman dashboard';

        // Tambahkan data statistik jika diperlukan
        $total_laporan_count = 100;
        $laporan_belum_diproses_count = 25;
        $laporan_sedang_diproses_count = 10;

        return view('admin.pages.dashboard.index', [
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'total_laporan_count' => $total_laporan_count,
            'laporan_belum_diproses_count' => $laporan_belum_diproses_count,
            'laporan_sedang_diproses_count' => $laporan_sedang_diproses_count,
        ]);
    }
}

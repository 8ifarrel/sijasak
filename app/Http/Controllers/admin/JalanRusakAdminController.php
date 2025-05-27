<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JalanRusak;
use Illuminate\Support\Facades\Storage;

class JalanRusakAdminController extends Controller
{
    public function index()
    {
        $page_title = 'Data Jalan Rusak';
        $meta_description = 'Meta description halaman data jalan rusak';
        $jalan_rusak = JalanRusak::all();

        return view('admin.pages.jalan-rusak.index', [
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'jalan_rusak' => $jalan_rusak,
        ]);
    }

    public function create()
    {
        $page_title = 'Tambah Jalan Rusak';
        $meta_description = 'Meta description halaman tambah jalan rusak';

        return view('admin.pages.jalan-rusak.create', [
            'page_title' => $page_title,
            'meta_description' => $meta_description,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'deskripsi' => 'required|string',
            'foto' => 'required|image|max:2048',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'tingkat_keparahan' => 'required|in:ringan,sedang,berat',
        ]);

        $fotoPath = $request->file('foto')->store('jalan_rusak', 'public');

        JalanRusak::create([
            'deskripsi' => $validated['deskripsi'],
            'foto' => $fotoPath,
            'longitude' => $validated['longitude'],
            'latitude' => $validated['latitude'],
            'tingkat_keparahan' => $validated['tingkat_keparahan'],
        ]);

        return redirect()->route('admin.jalan-rusak.index')->with('success', 'Data jalan rusak berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $page_title = 'Edit Jalan Rusak';
        $meta_description = 'Meta description halaman edit jalan rusak';
        $jalan_rusak = JalanRusak::findOrFail($id);

        return view('admin.pages.jalan-rusak.edit', [
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'jalan_rusak' => $jalan_rusak
        ]);
    }

    public function update(Request $request, $id)
    {
        $jalan_rusak = JalanRusak::findOrFail($id);

        $validated = $request->validate([
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|max:2048',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'tingkat_keparahan' => 'required|in:ringan,sedang,berat',
            'sudah_diperbaiki' => 'required|boolean',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            Storage::disk('public')->delete($jalan_rusak->foto);
            // Simpan foto baru
            $validated['foto'] = $request->file('foto')->store('jalan_rusak', 'public');
        }

        $jalan_rusak->update($validated);

        return redirect()->route('admin.jalan-rusak.index')
            ->with('success', 'Data jalan rusak berhasil diperbarui.');
    }
}

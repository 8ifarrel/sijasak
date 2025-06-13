<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JalanRusak;
use App\Models\FotoJalanRusak;
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
            'foto' => 'required|array|min:1',
            'foto.*' => 'image|max:2048',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'tingkat_keparahan' => 'required|in:ringan,sedang,berat',
        ]);

        $jalanRusak = JalanRusak::create([
            'deskripsi' => $validated['deskripsi'],
            'longitude' => $validated['longitude'],
            'latitude' => $validated['latitude'],
            'tingkat_keparahan' => $validated['tingkat_keparahan'],
        ]);

        foreach ($request->file('foto') as $fotoFile) {
            $fotoModel = new FotoJalanRusak([
                'jalan_rusak_id' => $jalanRusak->id,
                'foto' => '', // <-- tambahkan ini agar tidak error
            ]);
            $fotoModel->save();

            $ext = $fotoFile->getClientOriginalExtension();
            $path = "jalan-rusak/{$jalanRusak->id}/{$fotoModel->id}." . $ext;
            Storage::disk('public')->putFileAs(
                "jalan-rusak/{$jalanRusak->id}",
                $fotoFile,
                "{$fotoModel->id}.{$ext}"
            );
            $fotoModel->foto = $path;
            $fotoModel->save();
        }

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
            'foto' => 'nullable|array',
            'foto.*' => 'image|max:2048',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'tingkat_keparahan' => 'required|in:ringan,sedang,berat',
            'sudah_diperbaiki' => 'required|boolean',
        ]);

        $jalan_rusak->update([
            'deskripsi' => $validated['deskripsi'],
            'longitude' => $validated['longitude'],
            'latitude' => $validated['latitude'],
            'tingkat_keparahan' => $validated['tingkat_keparahan'],
            'sudah_diperbaiki' => $validated['sudah_diperbaiki'],
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $fotoFile) {
                $fotoModel = new FotoJalanRusak([
                    'jalan_rusak_id' => $jalan_rusak->id,
                    'foto' => '', 
                ]);
                $fotoModel->save();

                $ext = $fotoFile->getClientOriginalExtension();
                $path = "jalan-rusak/{$jalan_rusak->id}/{$fotoModel->id}." . $ext;
                Storage::disk('public')->putFileAs(
                    "jalan-rusak/{$jalan_rusak->id}",
                    $fotoFile,
                    "{$fotoModel->id}.{$ext}"
                );
                $fotoModel->foto = $path;
                $fotoModel->save();
            }
        }

        return redirect()->route('admin.jalan-rusak.index')
            ->with('success', 'Data jalan rusak berhasil diperbarui.');
    }

    public function deleteFoto($jalan_rusak_id, $foto_id)
    {
        $foto = FotoJalanRusak::where('jalan_rusak_id', $jalan_rusak_id)->where('id', $foto_id)->firstOrFail();
        // Hapus file dari storage jika ada
        if ($foto->foto && Storage::disk('public')->exists($foto->foto)) {
            Storage::disk('public')->delete($foto->foto);
        }
        $foto->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}

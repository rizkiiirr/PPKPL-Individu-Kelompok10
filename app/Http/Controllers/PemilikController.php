<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProyekModel;
use Illuminate\Support\Facades\Auth;

class PemilikController extends Controller
{
    // Dashboard pemilik
    public function index()
    {
        $proyek = ProyekModel::where('pemilik_id', Auth::id())
                    ->latest()
                    ->get();

        return view('pemilik.index', compact('proyek'));
    }

    // Form tambah proyek
    public function create()
    {
        return view('pemilik.create');
    }

    // Simpan proyek
    public function store(Request $request)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
        ]);

        ProyekModel::create([
            'pemilik_id' => Auth::id(),
            'nama_proyek' => $request->nama_proyek,
            'lokasi' => $request->lokasi,
            'status' => 'Menunggu Verifikasi Admin'
        ]);

        return redirect()
            ->route('pemilik.index')
            ->with('success', 'Proyek berhasil diajukan.');
    }
}
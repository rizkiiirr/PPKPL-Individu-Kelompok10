<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoilTestModel;
use App\Models\ProyekModel;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function index()
    {
        // Menampilkan riwayat pengajuan milik kontraktor yang sedang login
        $pengajuans = SoilTestModel::where('kontraktor_id', Auth::id())
                        ->with('proyek') // Load relasi proyek
                        ->get();
                        
        return view('kontraktor.pengajuan.index', compact('pengajuans'));
    }

    public function create()
    {
        // Mengambil daftar proyek untuk dipilih di dropdown form
        $proyeks = ProyekModel::all();
        return view('kontraktor.pengajuan.create', compact('proyeks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proyek_id' => 'required|exists:proyek,id',
            'jenis_pengujian' => 'required|string',
        ]);

        SoilTestModel::create([
            'proyek_id' => $request->proyek_id,
            'kontraktor_id' => Auth::id(), // Ambil ID user yang login
            'jenis_pengujian' => $request->jenis_pengujian,
            'status' => 'aktif', // Status awal sesuai User Journey
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Jadwal uji tanah berhasil diajukan!');
    }
}
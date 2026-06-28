<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoilTestModel;

class PetugasLabController extends Controller
{
    /**
     * Menampilkan seluruh pengajuan yang sudah memiliki hasil uji
     */
    public function index()
    {
        $soilTests = SoilTestModel::with([
            'proyek',
            'kontraktor',
            'location.hasilSondir',
            'soilCertificate'
        ])->get();

        return view(
            'petugas_lab.index',
            compact('soilTests')
        );
    }

    /**
     * Menentukan kelayakan hasil pengujian
     */
    public function updateKelayakan(
        Request $request,
        SoilTestModel $soilTest
    ) {

        $request->validate([
            'status' => 'required|in:Layak,Tidak Layak'
        ]);

        $soilTest->update([
            'status' => $request->status
        ]);

        return back()->with(
            'success',
            'Status kelayakan berhasil diperbarui.'
        );
    }
}
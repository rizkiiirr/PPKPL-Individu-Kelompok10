<?php

namespace App\Http\Controllers;

use App\Models\SoilTestModel;
use App\Models\SoilCertificate;
use Illuminate\Http\Request;

class SoilCertificateController extends Controller
{
    public function create(SoilTestModel $soilTest)
    {
        return view('petugas_lab.upload_sertif', compact('soilTest'));
    }

    public function store(Request $request, SoilTestModel $soilTest)
    {
        //     dd(
        //     $soilTest->status,
        //     $request->all(),
        //     $request->hasFile('sertifikat_uji'),
        //     $request->file('sertifikat_uji')
        // );
        // dd($soilTest->status);
        // // Validasi: hanya bisa upload jika sudah dipilih Layak/Tidak Layak
        // if (
        //     $soilTest->status !== 'Layak' &&
        //     $soilTest->status !== 'Tidak Layak'
        // ) {
        //     return back()->with(
        //         'error',
        //         'Tentukan status kelayakan terlebih dahulu.'
        //     );
        // }

        $request->validate([
            'sertifikat_uji' => 'required|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $file = $request->file('sertifikat_uji');

        $path = $file->store('certificates', 'public');

        SoilCertificate::create([
            'pengajuan_uji_tanah_id' => $soilTest->id,
            'file_path' => $path
        ]);

        return redirect()
            ->route('petugas_lab.index')
            ->with(
                'success',
                'Sertifikat berhasil diupload.'
            );
    }
}
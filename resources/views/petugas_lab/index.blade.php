<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-7xl mx-auto p-6">

    <div class="mb-6">
        <h1 class="text-3xl font-bold">
            Dashboard Petugas Lab
        </h1>

        <p class="text-gray-600">
            Kelola hasil pengujian, sertifikat, dan notifikasi.
        </p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">Proyek</th>
                    <th class="p-3 border">Jenis Pengujian</th>
                    <th class="p-3 border">Hasil Teknisi</th>
                    <th class="p-3 border">Kelayakan</th>
                    <th class="p-3 border">Upload Sertifikat</th>
                    <th class="p-3 border">Kirim Notifikasi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($soilTests as $soilTest)

                <tr>

                    <!-- Nama Proyek -->
                    <td class="border p-3">
                        {{ $soilTest->proyek->nama_proyek ?? '-' }}
                    </td>

                    <!-- Jenis Pengujian -->
                    <td class="border p-3">
                        {{ $soilTest->jenis_pengujian }}
                    </td>

                    <!-- Hasil Teknisi -->
                    <td class="border p-3">

                        @if(
                            $soilTest->location &&
                            $soilTest->location->hasilSondir
                        )

                            <div class="space-y-1">

                                <div>
                                    <span class="font-semibold">QC :</span>
                                    {{ $soilTest->location->hasilSondir->nilai_qc }}
                                </div>

                                <div>
                                    <span class="font-semibold">FS :</span>
                                    {{ $soilTest->location->hasilSondir->nilai_fs }}
                                </div>

                            </div>

                        @else

                            <span class="text-red-600 font-semibold">
                                Belum Ada Hasil
                            </span>

                        @endif

                    </td>

                    <!-- Kelayakan -->
                    <td class="border p-3">

                        <form
                            action="{{ route('lab.kelayakan.update',$soilTest->id) }}"
                            method="POST">

                            @csrf

                            <select
                                name="status"
                                class="border rounded p-2 mb-2 w-full">

                                <option value="">-- Pilih --</option>

                                <option value="Layak"
                                    @selected($soilTest->status == 'Layak')>
                                    Layak
                                </option>

                                <option value="Tidak Layak"
                                    @selected($soilTest->status == 'Tidak Layak')>
                                    Tidak Layak
                                </option>

                            </select>

                            <button
                                class="w-full px-3 py-2 rounded text-white

                                @if(
                                    $soilTest->location &&
                                    $soilTest->location->hasilSondir
                                )
                                    bg-green-500
                                @else
                                    bg-gray-400 cursor-not-allowed
                                @endif"

                                @if(
                                    !(
                                        $soilTest->status == 'Menunggu Upload Sertifikat'
                                        || $soilTest->status == 'Layak'
                                        || $soilTest->status == 'Tidak Layak'
                                    )
                                )
                                    disabled
                                @endif>

                                Simpan

                            </button>

                        </form>

                    </td>

                    <!-- Upload Sertifikat -->
                    <td class="border p-3 text-center">

                        @if(
                            $soilTest->status == 'Layak'
                            || $soilTest->status == 'Tidak Layak'
                        )

                            <a href="{{ route('lab.certificate.create',$soilTest->id) }}"
                               class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">

                                Upload

                            </a>

                        @else

                            <button
                                disabled
                                class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed">

                                Upload

                            </button>

                        @endif

                    </td>

                    <!-- Kirim Notifikasi -->
                    <td class="border p-3 text-center">

                        @if($soilTest->soilCertificate)

                            <form
                                action="{{ route('lab.notify',$soilTest->id) }}"
                                method="POST">

                                @csrf

                                <button
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">

                                    Kirim

                                </button>

                            </form>

                        @else

                            <button
                                disabled
                                class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed">

                                Kirim

                            </button>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6"
                        class="text-center py-6 text-gray-500">

                        Belum ada data pengujian.

                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <form method="POST"
          action="{{ route('logout') }}"
          class="mt-6">

        @csrf

        <button
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">

            Logout

        </button>

    </form>

</div>

</body>
</html>
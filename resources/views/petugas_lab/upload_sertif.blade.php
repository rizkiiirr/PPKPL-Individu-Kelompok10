<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Sertifikat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-2xl mx-auto py-10 px-6">

    <div class="bg-white rounded-xl shadow-lg p-8">

        <h1 class="text-2xl font-bold mb-2">
            Upload Sertifikat Pengujian
        </h1>

        <p class="text-gray-600 mb-6">
            Silakan upload sertifikat hasil pengujian tanah.
        </p>

        {{-- Error --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Success --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Informasi Pengujian --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6 border">

            <h2 class="font-semibold mb-3">
                Informasi Pengujian
            </h2>

            <div class="space-y-2">

                <p>
                    <span class="font-semibold">Nama Proyek :</span>
                    {{ $soilTest->proyek->nama_proyek ?? '-' }}
                </p>

                <p>
                    <span class="font-semibold">Jenis Pengujian :</span>
                    {{ $soilTest->jenis_pengujian }}
                </p>

                <p>
                    <span class="font-semibold">Status Kelayakan :</span>

                    @if($soilTest->status == 'Layak')
                        <span class="text-green-600 font-semibold">
                            Layak
                        </span>
                    @elseif($soilTest->status == 'Tidak Layak')
                        <span class="text-red-600 font-semibold">
                            Tidak Layak
                        </span>
                    @else
                        <span class="text-gray-600">
                            Belum Ditentukan
                        </span>
                    @endif
                </p>

            </div>

        </div>

        {{-- Form Upload --}}
        <form action="{{ route('lab.certificate.store', $soilTest->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="mb-6">

                <label class="block font-medium mb-2">
                    File Sertifikat
                </label>

                <input
                    type="file"
                    name="sertifikat_uji"
                    accept=".pdf,.jpg,.jpeg,.png"
                    required
                    class="w-full border rounded-lg px-4 py-3"
                >

                <p class="text-sm text-gray-500 mt-2">
                    Format yang diperbolehkan: PDF, JPG, JPEG, PNG
                </p>

                @error('sertifikat_uji')
                    <p class="text-red-500 text-sm mt-2">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            <div class="flex gap-3">

                <button
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">

                    Upload Sertifikat

                </button>

                <a href="{{ route('petugas_lab.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">

                    Kembali

                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>
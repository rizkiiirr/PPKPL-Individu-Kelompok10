<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Proyek</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="max-w-3xl mx-auto p-6">

    <div class="bg-white rounded-lg shadow p-6">

        <h1 class="text-2xl font-bold mb-6">
            Tambah Proyek Baru
        </h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('proyek.store') }}" method="POST">
            @csrf

            <!-- Nama Proyek -->
            <div class="mb-4">
                <label class="block font-semibold mb-2">
                    Nama Proyek
                </label>

                <input
                    type="text"
                    name="nama_proyek"
                    value="{{ old('nama_proyek') }}"
                    class="w-full border border-gray-300 rounded-lg p-3"
                    placeholder="Contoh: Pembangunan Rumah Pak Budi"
                    required
                >
            </div>

            <!-- Lokasi -->
            <div class="mb-6">
                <label class="block font-semibold mb-2">
                    Lokasi Proyek
                </label>

                <textarea
                    name="lokasi"
                    rows="4"
                    class="w-full border border-gray-300 rounded-lg p-3"
                    placeholder="Masukkan alamat lengkap proyek"
                    required>{{ old('lokasi') }}</textarea>
            </div>

            <!-- Tombol -->
            <div class="flex gap-3">

                <button
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg">
                    Simpan Proyek
                </button>

                <a href="{{ route('pemilik.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>
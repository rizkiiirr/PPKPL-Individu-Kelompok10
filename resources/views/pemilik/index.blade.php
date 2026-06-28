<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pemilik Rumah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="max-w-6xl mx-auto p-6">

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold">
            Dashboard Pemilik Rumah
        </h1>

        <p class="text-gray-600 mt-2">
            Selamat datang,
            <span class="font-semibold">
                {{ auth()->user()->nama }}
            </span>
        </p>
    </div>

    <!-- Flash Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tambah Proyek -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-2">
            Pengajuan Proyek Baru
        </h2>

        <p class="text-gray-600 mb-4">
            Ajukan proyek baru untuk dilakukan pengujian tanah.
        </p>

        <a href="{{ route('proyek.create') }}"
           class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Tambah Proyek
        </a>
    </div>

    <!-- Daftar Proyek -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">
            Daftar Proyek Saya
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">No</th>
                        <th class="border px-4 py-2">Nama Proyek</th>
                        <th class="border px-4 py-2">Lokasi</th>
                        <th class="border px-4 py-2">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($proyek as $item)
                    <tr>
                        <td class="border px-4 py-2 text-center">
                            {{ $loop->iteration }}
                        </td>

                        <td class="border px-4 py-2">
                            {{ $item->nama_proyek }}
                        </td>

                        <td class="border px-4 py-2">
                            {{ $item->lokasi }}
                        </td>

                        <td class="border px-4 py-2">
                            <span class="px-3 py-1 rounded-full text-sm
                                @if($item->status == 'Menunggu Verifikasi Admin')
                                    bg-yellow-100 text-yellow-700
                                @elseif($item->status == 'Disetujui')
                                    bg-green-100 text-green-700
                                @else
                                    bg-gray-100 text-gray-700
                                @endif">
                                {{ $item->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">
                            Belum ada proyek yang diajukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Notifikasi -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-2">
            Hasil Kelayakan Fondasi
        </h2>

        <p class="text-gray-600 mb-4">
            Lihat hasil verifikasi kelayakan fondasi proyek Anda.
        </p>

        <a href="{{ route('notifications.index') }}"
           class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded">
            Lihat Notifikasi
        </a>
    </div>

    <!-- Logout -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
            Logout
        </button>
    </form>

</div>

</body>
</html>
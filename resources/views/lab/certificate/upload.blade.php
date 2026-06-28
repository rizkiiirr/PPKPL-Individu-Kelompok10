<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Sertifikat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen font-sans">

    <div class="w-full max-w-md bg-white border rounded-xl shadow-sm p-6">

        <!-- Title -->
        <h2 class="text-lg font-semibold mb-4 text-center">
            Upload Sertifikat
        </h2>

        <!-- Form -->
        <form action="{{ route('lab.certificate.store', $soilTest->id) }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf

            <!-- File Input -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Pilih File Sertifikat
                </label>
                <input 
                    type="file" 
                    name="sertifikat_uji" 
                    required
                    accept=".pdf,.jpg,.png"
                    class="w-full border rounded-lg px-3 py-2 bg-white 
                           file:mr-4 file:py-2 file:px-3 file:rounded-lg 
                           file:border-0 file:bg-blue-500 file:text-white 
                           hover:file:bg-blue-600"
                >

                <p class="text-xs text-gray-500 mt-1">
                    Format: PDF / JPG / PNG
                </p>

                @error('sertifikat_uji')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Button -->
            <button 
                type="submit"
                onclick="this.disabled=true; this.innerText='Uploading...'"
                class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition"
            >
                Upload Sertifikat
            </button>

        </form>

        <!-- Back -->
        <div class="mt-4 text-center">
            <a href="{{ route('petugas_lab.index') }}" class="text-blue-500 text-sm hover:underline">
                ← Kembali ke Dashboard
            </a>
        </div>

    </div>

</body>
</html>
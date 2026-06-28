<?php

namespace App\Http\Controllers;

use App\Models\SoilTestModel;
use App\Notifications\FoundationStatusNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function send(SoilTestModel $soilTest)
    {
        // Hanya bisa kirim jika sudah ditentukan kelayakannya
        if (
            $soilTest->status !== 'Layak' &&
            $soilTest->status !== 'Tidak Layak'
        ) {
            return back()->with(
                'error',
                'Status kelayakan belum ditentukan!'
            );
        }

        // Pastikan sertifikat sudah diupload
        if (!$soilTest->soilCertificate) {
            return back()->with(
                'error',
                'Sertifikat belum diupload!'
            );
        }

        $owner = $soilTest->proyek->pemilik;

        $owner->notify(
            new FoundationStatusNotification($soilTest)
        );

        return back()->with(
            'success',
            'Notifikasi berhasil dikirim ke pemilik rumah.'
        );
    }

    public function index()
    {
        $notifications = Auth::user()->notifications;

        return view('notifications.index', compact('notifications'));
    }
}
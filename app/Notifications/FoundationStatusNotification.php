<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FoundationStatusNotification extends Notification
{
    use Queueable;

    protected $soilTest;

    public function __construct($soilTest)
    {
        $this->soilTest = $soilTest;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [

            'title' => 'Status Kelayakan Fondasi',

            'message' =>
                'Hasil pengujian '
                . $this->soilTest->jenis_pengujian
                . ' untuk proyek '
                . $this->soilTest->proyek->nama_proyek
                . ' dinyatakan '
                . $this->soilTest->status,

            'status' => $this->soilTest->status,

            'jenis_pengujian' =>
                $this->soilTest->jenis_pengujian,
        ];
    }
}
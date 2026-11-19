<?php

namespace App\Support;

class EquipmentCatalog
{
    public static function options(): array
    {
        return [
            'Perangkat Komputer & Aksesori' => [
                'Laptop',
                'Mouse Wireless / Kabel',
                'Keyboard Eksternal',
                'Monitor LCD/LED',
                'Webcam HD',
                'Headset / Headphone dengan Mic',
                'Speaker Aktif Portable',
                'Drawing Tablet / Pen Tablet (Wacom / Huion)',
                'Flashdisk / USB Drive',
                'Harddisk Eksternal / SSD Eksternal',
            ],
            'Perangkat Jaringan (Networking)' => [
                'Router MikroTik',
                'Switch Hub (8 Port / 16 Port)',
                'Access Point (Wi-Fi)',
                'Tang Crimping (Crimping Tool)',
                'LAN Tester (Cable Tester)',
                'Kabel LAN UTP (Patch Cord)',
                'USB to LAN Adapter (RJ45)',
                'USB Wi-Fi Adapter',
                'Modem Mifi / Dongle',
            ],
            'Perangkat Multimedia & Presentasi' => [
                'Proyektor LCD / DLP',
                'Layar Proyektor Portable (Tripod Screen)',
                'Laser Pointer / Presenter Wireless',
                'Kamera DSLR / Mirrorless',
                'Tripod Kamera',
                'Microphone Wireless / Clip-on',
                'Lighting Studio / Ring Light',
            ],
        ];
    }

    public static function names(): array
    {
        return array_merge(...array_values(self::options()));
    }
}

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Persetujuan Admin Sebelum Dana Dikirim
    |--------------------------------------------------------------------------
    | true  : permintaan penarikan berhenti di status PENDING. Saldo user
    |         sudah ditahan, tapi TIDAK ada apa pun yang dikirim ke gateway
    |         sampai admin menekan Approve. Selama masih PENDING, penarikan
    |         masih bisa dibatalkan user maupun ditolak admin dengan aman -
    |         karena belum ada uang yang bergerak di luar sistem kita.
    |
    | false : permintaan langsung dikirim ke gateway begitu diajukan.
    |         Lebih cepat untuk user, tapi begitu terkirim tidak ada lagi
    |         yang bisa membatalkannya; hanya gateway yang boleh memutuskan.
    |
    | Default true. Sekali dana lepas ke gateway, kesalahan penilaian tidak
    | bisa diperbaiki - dan itu sudah pernah terjadi (7 Agustus 2026).
    */
    'require_approval' => (bool) env('WITHDRAW_REQUIRE_APPROVAL', true),

    /*
    |--------------------------------------------------------------------------
    | Batas Nominal & Biaya Admin
    |--------------------------------------------------------------------------
    | Dikumpulkan di sini supaya angkanya tidak tercecer. Sebelumnya biaya
    | withdraw normal 5% sementara alat testing admin memakai angka mati
    | Rp 7.800, sehingga hasil pengujian tidak mewakili yang sebenarnya.
    */
    'fee_percent' => (float) env('WITHDRAW_FEE_PERCENT', 5),
    'min' => (int) env('WITHDRAW_MIN', 50000),
    'max' => (int) env('WITHDRAW_MAX', 50000000),

    /*
    |--------------------------------------------------------------------------
    | Jam Layanan Penarikan
    |--------------------------------------------------------------------------
    | Di luar jam ini tombol penarikan dimatikan dan pengajuan ditolak server.
    | Tujuannya supaya pencairan hanya berjalan saat ada admin yang memantau -
    | penting sekarang karena setiap penarikan butuh persetujuan manual.
    |
    | Zona waktu ditulis eksplisit karena server berjalan pada UTC, sementara
    | jam yang dimaksud owner adalah WIB.
    |
    | Jam boleh melewati tengah malam (mis. 21:00 - 03:00), itu ikut ditangani.
    */
    'hours' => [
        'enabled' => (bool) env('WITHDRAW_HOURS_ENABLED', true),
        'start' => env('WITHDRAW_HOURS_START', '09:00'),
        'end' => env('WITHDRAW_HOURS_END', '21:00'),
        'timezone' => env('WITHDRAW_HOURS_TIMEZONE', 'Asia/Jakarta'),
        'label' => env('WITHDRAW_HOURS_LABEL', 'WIB'),
    ],

];

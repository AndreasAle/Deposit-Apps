<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Saluran Pembayaran Deposit
    |--------------------------------------------------------------------------
    | User memilih sendiri saluran mana yang dipakai setiap kali deposit.
    | Dua saluran berjalan berdampingan:
    |
    | 'bankpay'      : payment gateway BankPay. Invoice dibuat lewat API,
    |                  pembayaran dikonfirmasi OTOMATIS lewat notify server
    |                  (dan poller status sebagai cadangan). Mendukung
    |                  deposit maupun withdraw.
    |
    | 'qris_statis'  : QRIS statis milik sendiri + nominal unik, tanpa gateway
    |                  dan tanpa MDR. Dikonfirmasi lewat notification listener
    |                  di HP (MacroDroid) — lihat ListenerController +
    |                  MutationMatcher. Deposit saja, tidak bisa withdraw.
    |
    | Saluran bisa dimatikan lewat .env tanpa mengubah kode. Kalau hanya satu
    | yang aktif, pemilih saluran di halaman deposit otomatis disembunyikan.
    */
    'default_channel' => env('DEPOSIT_DEFAULT_CHANNEL', 'bankpay'),

    'channels' => [

        'bankpay' => [
            'enabled' => (bool) env('DEPOSIT_CHANNEL_BANKPAY', true),
            'name' => 'Saluran Pembayaran 1',
            'desc' => 'Pembayaran otomatis, cepat, dan aman',
            'auto_confirm' => true,

            /*
            | Tandai saluran sebagai SEDANG BERMASALAH tanpa mematikannya.
            |
            | Bedanya dengan enabled=false: saluran tetap bisa dipilih user
            | (siapa tahu sudah pulih), tapi tidak lagi jadi pilihan default,
            | kartunya diberi label gangguan, dan pengumuman muncul di atas
            | halaman deposit. Dipakai saat gateway mengalami gangguan agar
            | user langsung diarahkan ke saluran yang sehat.
            |
            | Bisa dinyalakan/dimatikan lewat .env tanpa deploy - cukup
            | `php artisan config:cache` sesudahnya.
            */
            'degraded' => (bool) env('DEPOSIT_CHANNEL_BANKPAY_DEGRADED', false),
        ],

        'qris_statis' => [
            'enabled' => (bool) env('DEPOSIT_CHANNEL_QRIS_STATIS', true),
            'name' => 'Saluran Pembayaran 2',
            'desc' => 'Saluran alternatif pembayaran otomatis',
            'auto_confirm' => false,
            'degraded' => (bool) env('DEPOSIT_CHANNEL_QRIS_STATIS_DEGRADED', false),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pengumuman di Halaman Deposit
    |--------------------------------------------------------------------------
    | Teks bebas yang muncul sebagai spanduk di atas halaman deposit.
    | Kosongkan untuk menyembunyikannya.
    |
    | Kalau dikosongkan TAPI ada saluran yang ditandai bermasalah, pengumuman
    | otomatis disusun sendiri - jadi saat gangguan Anda cukup menyalakan satu
    | tanda saja, tidak perlu mengarang kalimat di tengah malam.
    */
    'notice' => env('DEPOSIT_NOTICE'),

    'qris' => [

        /*
        | String EMVCo QRIS statis milik merchant. Ambil dari hasil decode QR
        | statis yang dicetak/diberikan penyelenggara QRIS.
        */
        'statis' => env('QRIS_STATIS'),

        /*
        | Rentang kode unik yang ditambahkan ke nominal.
        | Ini juga menentukan berapa banyak deposit UNPAID yang bisa hidup
        | bersamaan untuk satu nominal dasar yang sama.
        */
        'kode_min' => (int) env('QRIS_KODE_MIN', 1),
        'kode_max' => (int) env('QRIS_KODE_MAX', 999),

        /*
        | Umur invoice (menit). Sengaja pendek: selama UNPAID, nominal uniknya
        | terkunci dan tidak bisa dipakai deposit lain. Makin pendek makin
        | banyak kapasitas nominal yang berputar.
        */
        'expiry_minutes' => (int) env('QRIS_EXPIRY_MINUTES', 30),

        /*
        | Toleransi notifikasi telat (menit). Pembayaran yang masuk setelah
        | invoice EXPIRED masih dicocokkan, tapi ditandai butuh review admin
        | supaya tidak otomatis menambah saldo.
        */
        'late_grace_minutes' => (int) env('QRIS_LATE_GRACE_MINUTES', 60),

        /*
        | true  : saldo ditambah sebesar nominal yang BENAR-BENAR dibayar
        |         (termasuk kode unik). User minta 50.000, bayar 50.123,
        |         saldo bertambah 50.123.
        | false : saldo ditambah sebesar nominal yang diminta (50.000).
        |
        | Default true supaya user tidak pernah merasa kehilangan uang.
        */
        'credit_paid_amount' => (bool) env('QRIS_CREDIT_PAID_AMOUNT', true),
    ],

    'listener' => [

        /*
        | Token bearer untuk HP listener (MacroDroid). WAJIB diisi di .env
        | dengan string acak panjang. Tanpa ini endpoint listener ditolak.
        */
        'token' => env('LISTENER_TOKEN'),

        /*
        | Listener dianggap offline kalau tidak mengirim heartbeat selama
        | sekian detik.
        */
        'heartbeat_timeout' => (int) env('LISTENER_HEARTBEAT_TIMEOUT', 120),
    ],
];

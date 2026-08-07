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

];

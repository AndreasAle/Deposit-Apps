<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('activity_log')) {
    function activity_log($adminId, $action, $payload)
    {
        Log::info('[ADMIN]', [
            'admin_id' => $adminId,
            'action'   => $action,
            'payload'  => $payload,
            'ip'       => request()->ip(),
            'time'     => now()->toDateTimeString(),
        ]);
    }
}

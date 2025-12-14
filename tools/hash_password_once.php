<?php
require_once '../config/supabase_request.php';

// Ambil semua user
$users = supabase_request('GET', '/rest/v1/user');

foreach ($users as $u) {

    // Skip kalau sudah hash
    if (str_starts_with($u['password'], '$2y$')) {
        continue;
    }

    $hash = password_hash($u['password'], PASSWORD_DEFAULT);

    supabase_request(
        'PATCH',
        "/rest/v1/user?id_user=eq.{$u['id_user']}",
        ['password' => $hash]
    );

    echo "User {$u['username']} di-hash<br>";
}

echo "SELESAI";

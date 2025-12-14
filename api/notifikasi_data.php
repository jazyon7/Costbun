<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/supabase_helper.php';

$notifikasiList = getNotifikasi();

if (!empty($notifikasiList)) {
    $result = [];
    foreach($notifikasiList as $notif) {
        $result[] = [
            'id' => $notif['id_notif'],
            'tipe' => $notif['tipe'],
            'judul' => $notif['judul'],
            'pesan' => $notif['pesan'],
            'tanggal' => $notif['tanggal_kirim'],
            'status' => $notif['status'],
            'nama_user' => isset($notif['user']['nama']) ? $notif['user']['nama'] : 'Unknown'
        ];
    }
    echo json_encode($result);
} else {
    echo json_encode([]);
}

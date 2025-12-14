<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/supabase_helper.php';

// Get query parameters
$userId = $_GET['user_id'] ?? null;
$type = $_GET['type'] ?? null;

try {
    // Fetch all notifications
    $notifikasiList = getNotifikasi();
    
    // Debug log
    error_log("Total notifikasi fetched: " . count($notifikasiList));
    
    // Ensure it's an array
    if (!is_array($notifikasiList)) {
        $notifikasiList = [];
    }
    
    // Filter by user_id if provided (for penghuni role)
    if ($userId !== null && !empty($notifikasiList)) {
        $notifikasiList = array_filter($notifikasiList, function($notif) use ($userId) {
            return isset($notif['id_user']) && (int)$notif['id_user'] === (int)$userId;
        });
    }
    
    // Filter by type if provided and not 'semua'
    if ($type !== null && $type !== 'semua' && !empty($notifikasiList)) {
        $notifikasiList = array_filter($notifikasiList, function($notif) use ($type) {
            return isset($notif['tipe']) && strtolower($notif['tipe']) === strtolower($type);
        });
    }
    
    // Reindex array after filter
    $notifikasiList = array_values($notifikasiList);
    
    // Sort by id descending (newest first)
    if (!empty($notifikasiList)) {
        usort($notifikasiList, function($a, $b) {
            return (int)$b['id_notif'] - (int)$a['id_notif'];
        });
    }
    
    // Format output
    $result = [];
    foreach($notifikasiList as $notif) {
        $result[] = [
            'id_notif' => $notif['id_notif'] ?? 0,
            'tipe' => $notif['tipe'] ?? 'info',
            'judul' => $notif['judul'] ?? '',
            'pesan' => $notif['pesan'] ?? '',
            'tanggal_kirim' => $notif['tanggal_kirim'] ?? date('Y-m-d'),
            'status' => $notif['status'] ?? 'unread',
            'id_user' => $notif['id_user'] ?? null,
            'nama_user' => isset($notif['user']['nama']) ? $notif['user']['nama'] : 'Unknown'
        ];
    }
    
    echo json_encode($result);
    
} catch (Exception $e) {
    error_log("Error in notifikasi_data.php: " . $e->getMessage());
    echo json_encode([]);
}

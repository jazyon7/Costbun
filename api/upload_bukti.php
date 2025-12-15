<?php
require_once __DIR__ . '/../config/supabase_helper.php';
session_start();

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['id_user'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu']);
    exit;
}

$id_user = $_SESSION['id_user'];
$id_tagihan = $_POST['id_tagihan'] ?? null;

if (!$id_tagihan) {
    echo json_encode(['success' => false, 'message' => 'ID tagihan tidak valid']);
    exit;
}

// Verify that the tagihan belongs to the logged-in user
$tagihan = getTagihan($id_tagihan);
if (!$tagihan || $tagihan['id_user'] != $id_user) {
    echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki akses ke tagihan ini']);
    exit;
}

// Check if file is uploaded
if (!isset($_FILES['bukti_pembayaran']) || $_FILES['bukti_pembayaran']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'File tidak valid atau gagal diupload']);
    exit;
}

$file = $_FILES['bukti_pembayaran'];

// Validate file type
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Format file tidak valid. Hanya JPG, JPEG, dan PNG yang diizinkan']);
    exit;
}

// Validate file size (max 2MB)
$maxSize = 2 * 1024 * 1024; // 2MB
if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar. Maksimal 2MB']);
    exit;
}

error_log("Uploading bukti pembayaran for tagihan #$id_tagihan by user #$id_user");

// Upload to Supabase Storage
$uploadResult = uploadToSupabaseStorage($file, 'uploads', 'pembayaran');

if (!$uploadResult['success']) {
    error_log("Upload failed: " . json_encode($uploadResult));
    echo json_encode(['success' => false, 'message' => 'Gagal mengupload file ke Supabase Storage']);
    exit;
}

$buktiUrl = $uploadResult['url'];
error_log("File uploaded successfully: $buktiUrl");

// Update tagihan with bukti_pembayaran URL and change status to pending
$updateResult = updateTagihan($id_tagihan, [
    'bukti_pembayaran' => $buktiUrl,
    'status_pembayaran' => 'pending'
]);

if (isset($updateResult['error'])) {
    error_log("Failed to update tagihan: " . json_encode($updateResult));
    // Try to delete uploaded file
    deleteFromSupabaseStorage('uploads', $uploadResult['path']);
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan bukti pembayaran']);
    exit;
}

// Create notification for admin
createNotifikasi([
    'tipe' => 'pembayaran',
    'judul' => 'Bukti Pembayaran Baru',
    'pesan' => 'Penghuni ' . $_SESSION['nama'] . ' telah mengupload bukti pembayaran. Mohon verifikasi.',
    'tanggal_kirim' => date('Y-m-d'),
    'status' => 'unread',
    'dikirim_n8n' => 'no',
    'id_user' => 1 // Admin
]);

echo json_encode([
    'success' => true, 
    'message' => 'Bukti pembayaran berhasil diupload dan menunggu verifikasi',
    'url' => $buktiUrl
]);

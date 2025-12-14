<?php
require_once __DIR__ . '/../config/supabase_helper.php';
session_start();

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// Check if user is admin for approve/reject actions
if (($action === 'approve' || $action === 'reject') && 
    (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak. Hanya admin yang dapat mengelola pembayaran']);
    exit;
}

switch ($action) {
    case 'approve':
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID tagihan tidak valid']);
            exit;
        }
        
        error_log("Approve payment - ID: $id");
        
        // Update status pembayaran menjadi lunas
        $result = updateTagihan($id, [
            'status_pembayaran' => 'lunas'
        ]);
        
        if (isset($result['error'])) {
            echo json_encode([
                'success' => false, 
                'message' => 'Gagal approve pembayaran: ' . ($result['message'] ?? 'Unknown error')
            ]);
        } else {
            // Optional: Create notification for user
            $tagihan = getTagihan($id);
            if ($tagihan && isset($tagihan['id_user'])) {
                createNotifikasi([
                    'tipe' => 'pembayaran',
                    'judul' => 'Pembayaran Disetujui',
                    'pesan' => 'Pembayaran Anda sebesar Rp ' . number_format($tagihan['jumlah'], 0, ',', '.') . ' telah disetujui dan status berubah menjadi LUNAS.',
                    'tanggal_kirim' => date('Y-m-d'),
                    'status' => 'unread',
                    'dikirim_n8n' => 'no',
                    'id_user' => $tagihan['id_user']
                ]);
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Pembayaran berhasil di-approve',
                'data' => $result
            ]);
        }
        break;
    
    case 'reject':
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID tagihan tidak valid']);
            exit;
        }
        
        $input = json_decode(file_get_contents("php://input"), true);
        $reason = $input['reason'] ?? 'Bukti pembayaran tidak valid';
        
        error_log("Reject payment - ID: $id, Reason: $reason");
        
        // Update status pembayaran menjadi belum_lunas dan hapus bukti
        $result = updateTagihan($id, [
            'status_pembayaran' => 'belum_lunas',
            'bukti_pembayaran' => ''
        ]);
        
        if (isset($result['error'])) {
            echo json_encode([
                'success' => false, 
                'message' => 'Gagal reject pembayaran: ' . ($result['message'] ?? 'Unknown error')
            ]);
        } else {
            // Create notification for user
            $tagihan = getTagihan($id);
            if ($tagihan && isset($tagihan['id_user'])) {
                createNotifikasi([
                    'tipe' => 'pembayaran',
                    'judul' => 'Pembayaran Ditolak',
                    'pesan' => 'Pembayaran Anda ditolak. Alasan: ' . $reason . '. Silakan upload bukti pembayaran yang valid.',
                    'tanggal_kirim' => date('Y-m-d'),
                    'status' => 'unread',
                    'dikirim_n8n' => 'no',
                    'id_user' => $tagihan['id_user']
                ]);
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Pembayaran berhasil di-reject',
                'data' => $result
            ]);
        }
        break;
    
    case 'create':
        parse_str(file_get_contents("php://input"), $input);
        
        $data = [
            'jumlah' => (int)($input['jumlah'] ?? 0),
            'tgl_tagihan' => $input['tgl_tagihan'] ?? date('Y-m-d'),
            'tgl_tempo' => $input['tgl_tempo'] ?? date('Y-m-d'),
            'status_pembayaran' => $input['status_pembayaran'] ?? 'belum_lunas',
            'metode_pembayaran' => $input['metode_pembayaran'] ?? '',
            'bukti_pembayaran' => $input['bukti_pembayaran'] ?? '',
            'id_user' => (int)($input['id_user'] ?? 0),
            'id_kamar' => (int)($input['id_kamar'] ?? 0)
        ];
        
        $result = createTagihan($data);
        
        if (isset($result['error'])) {
            echo json_encode(['success' => false, 'message' => 'Gagal menambah tagihan']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Tagihan berhasil ditambahkan']);
        }
        break;
    
    case 'update':
        $id = $_GET['id'] ?? null;
        parse_str(file_get_contents("php://input"), $input);
        
        if ($id && !empty($input)) {
            $result = updateTagihan($id, $input);
            echo json_encode(['success' => true, 'message' => 'Tagihan berhasil diupdate']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        }
        break;
    
    case 'delete':
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $result = deleteTagihan($id);
            echo json_encode(['success' => true, 'message' => 'Tagihan berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
        }
        break;
    
    case 'get':
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $result = getTagihan($id);
            echo json_encode($result);
        } else {
            $result = getTagihan();
            echo json_encode($result);
        }
        break;
    
    default:
        echo json_encode(['error' => 'Action tidak valid']);
        break;
}

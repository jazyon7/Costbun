<?php
require_once __DIR__ . '/../config/supabase_helper.php';

$action = $_GET['action'] ?? '';

switch ($action) {
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

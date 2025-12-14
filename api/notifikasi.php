<?php
require_once __DIR__ . '/../config/supabase_helper.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        parse_str(file_get_contents("php://input"), $input);
        
        $data = [
            'tipe' => $input['tipe'] ?? '',
            'judul' => $input['judul'] ?? '',
            'pesan' => $input['pesan'] ?? '',
            'tanggal_kirim' => $input['tanggal_kirim'] ?? date('Y-m-d'),
            'status' => $input['status'] ?? 'unread',
            'dikirim_n8n' => $input['dikirim_n8n'] ?? 'false',
            'id_user' => (int)($input['id_user'] ?? 0)
        ];
        
        $result = createNotifikasi($data);
        
        if (isset($result['error'])) {
            echo json_encode(['success' => false, 'message' => 'Gagal menambah notifikasi']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Notifikasi berhasil ditambahkan']);
        }
        break;
    
    case 'update':
        $id = $_GET['id'] ?? null;
        parse_str(file_get_contents("php://input"), $input);
        
        if ($id && !empty($input)) {
            $result = updateNotifikasi($id, $input);
            echo json_encode(['success' => true, 'message' => 'Notifikasi berhasil diupdate']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        }
        break;
    
    case 'delete':
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $result = deleteNotifikasi($id);
            header("Location: ../index.php?page=notifikasi&msg=Notifikasi berhasil dihapus");
        } else {
            header("Location: ../index.php?page=notifikasi&error=ID tidak valid");
        }
        break;
    
    case 'get':
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $result = getNotifikasi($id);
            echo json_encode($result);
        } else {
            $result = getNotifikasi();
            echo json_encode($result);
        }
        break;
    
    default:
        echo json_encode(['error' => 'Action tidak valid']);
        break;
}

<?php
require_once __DIR__ . '/../config/supabase_helper.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'update_status':
        $id = $_GET['id'] ?? null;
        $status = $_GET['status'] ?? null;
        
        if ($id && $status) {
            $result = updateLaporan($id, ['status_laporan' => $status]);
            header("Location: ../index.php?page=laporan&msg=Status laporan berhasil diubah");
        } else {
            header("Location: ../index.php?page=laporan&error=Data tidak lengkap");
        }
        break;
    
    case 'create':
        parse_str(file_get_contents("php://input"), $input);
        
        $data = [
            'judul_laporan' => $input['judul_laporan'] ?? '',
            'deskripsi' => $input['deskripsi'] ?? '',
            'status_laporan' => $input['status_laporan'] ?? 'diproses',
            'source' => $input['source'] ?? 'web',
            'gambar_url' => $input['gambar_url'] ?? '',
            'id_user' => (int)($input['id_user'] ?? 0)
        ];
        
        $result = createLaporan($data);
        
        if (isset($result['error'])) {
            echo json_encode(['success' => false, 'message' => 'Gagal menambah laporan']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Laporan berhasil ditambahkan']);
        }
        break;
    
    case 'update':
        $id = $_GET['id'] ?? null;
        parse_str(file_get_contents("php://input"), $input);
        
        if ($id && !empty($input)) {
            $result = updateLaporan($id, $input);
            echo json_encode(['success' => true, 'message' => 'Laporan berhasil diupdate']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        }
        break;
    
    case 'delete':
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $result = deleteLaporan($id);
            header("Location: ../index.php?page=laporan&msg=Laporan berhasil dihapus");
        } else {
            header("Location: ../index.php?page=laporan&error=ID tidak valid");
        }
        break;
    
    case 'get':
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $result = getLaporan($id);
            echo json_encode($result);
        } else {
            $result = getLaporan();
            echo json_encode($result);
        }
        break;
    
    default:
        echo json_encode(['error' => 'Action tidak valid']);
        break;
}

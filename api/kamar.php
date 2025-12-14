<?php
require_once __DIR__ . '/../config/supabase_helper.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'update_status':
        $id = $_GET['id'] ?? null;
        $status = $_GET['status'] ?? null;
        
        if ($id && $status) {
            $result = updateKamar($id, ['status' => $status]);
            header("Location: ../index.php?page=data_kamar&msg=Status kamar berhasil diubah");
        } else {
            header("Location: ../index.php?page=data_kamar&error=Data tidak lengkap");
        }
        break;
    
    case 'create':
        $data = [
            'nama' => $_GET['nama'] ?? '',
            'kasur' => (int)($_GET['kasur'] ?? 1),
            'kipas' => (int)($_GET['kipas'] ?? 1),
            'lemari' => (int)($_GET['lemari'] ?? 1),
            'keranjang_sampah' => (int)($_GET['keranjang_sampah'] ?? 1),
            'ac' => (int)($_GET['ac'] ?? 0),
            'harga' => (int)($_GET['harga'] ?? 0),
            'status' => $_GET['status'] ?? 'kosong'
        ];
        
        $result = createKamar($data);
        header("Location: ../index.php?page=data_kamar&msg=Kamar berhasil ditambahkan");
        break;
    
    case 'update':
        $id = $_GET['id'] ?? null;
        parse_str(file_get_contents("php://input"), $input);
        
        if ($id && !empty($input)) {
            $result = updateKamar($id, $input);
            echo json_encode(['success' => true, 'message' => 'Kamar berhasil diupdate']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        }
        break;
    
    case 'delete':
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $result = deleteKamar($id);
            header("Location: ../index.php?page=data_kamar&msg=Kamar berhasil dihapus");
        } else {
            header("Location: ../index.php?page=data_kamar&error=ID tidak valid");
        }
        break;
    
    case 'get':
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $result = getKamar($id);
            echo json_encode($result);
        } else {
            $result = getKamar();
            echo json_encode($result);
        }
        break;
    
    default:
        echo json_encode(['error' => 'Action tidak valid']);
        break;
}

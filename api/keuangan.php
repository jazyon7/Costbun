<?php
require_once __DIR__ . '/../config/supabase_helper.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        parse_str(file_get_contents("php://input"), $input);
        
        $data = [
            'tanggal_tranksaksi' => $input['tanggal_tranksaksi'] ?? date('Y-m-d'),
            'jenis' => $input['jenis'] ?? '',
            'keterangan' => $input['keterangan'] ?? '',
            'jumlah' => (int)($input['jumlah'] ?? 0),
            'sumber' => $input['sumber'] ?? ''
        ];
        
        $result = createKeuangan($data);
        
        if (isset($result['error'])) {
            echo json_encode(['success' => false, 'message' => 'Gagal menambah keuangan']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Keuangan berhasil ditambahkan']);
        }
        break;
    
    case 'update':
        $id = $_GET['id'] ?? null;
        parse_str(file_get_contents("php://input"), $input);
        
        if ($id && !empty($input)) {
            $result = updateKeuangan($id, $input);
            echo json_encode(['success' => true, 'message' => 'Keuangan berhasil diupdate']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        }
        break;
    
    case 'delete':
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $result = deleteKeuangan($id);
            echo json_encode(['success' => true, 'message' => 'Keuangan berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
        }
        break;
    
    case 'get':
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $result = getKeuangan($id);
            echo json_encode($result);
        } else {
            $result = getKeuangan();
            echo json_encode($result);
        }
        break;
    
    default:
        echo json_encode(['error' => 'Action tidak valid']);
        break;
}

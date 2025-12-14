<?php
require_once __DIR__ . '/../config/supabase_helper.php';
session_start();

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// Check admin role for write operations
if (in_array($action, ['create', 'update', 'delete'])) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Akses ditolak. Hanya admin yang bisa mengelola keuangan']);
        exit;
    }
}

switch ($action) {
    case 'create':
        // Parse JSON request
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
            exit;
        }
        
        error_log("Create keuangan - Input: " . json_encode($input));
        
        // Validasi required fields
        if (empty($input['tanggal_tranksaksi']) || empty($input['jenis']) || 
            empty($input['keterangan']) || empty($input['sumber']) || !isset($input['jumlah'])) {
            echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
            exit;
        }
        
        $data = [
            'tanggal_tranksaksi' => $input['tanggal_tranksaksi'],
            'jenis' => strtolower($input['jenis']),
            'keterangan' => trim($input['keterangan']),
            'jumlah' => (int)$input['jumlah'],
            'sumber' => trim($input['sumber'])
        ];
        
        error_log("Create keuangan - Data: " . json_encode($data));
        
        $result = createKeuangan($data);
        
        error_log("Create keuangan - Result: " . json_encode($result));
        
        if (isset($result['error'])) {
            echo json_encode([
                'success' => false, 
                'message' => 'Gagal menambah transaksi: ' . ($result['message'] ?? 'Unknown error')
            ]);
        } else {
            echo json_encode([
                'success' => true, 
                'message' => 'Transaksi berhasil ditambahkan',
                'data' => $result
            ]);
        }
        break;
    
    case 'update':
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
            exit;
        }
        
        // Parse JSON request
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
            exit;
        }
        
        error_log("Update keuangan - ID: $id, Input: " . json_encode($input));
        
        $data = [];
        if (isset($input['tanggal_tranksaksi'])) $data['tanggal_tranksaksi'] = $input['tanggal_tranksaksi'];
        if (isset($input['jenis'])) $data['jenis'] = strtolower($input['jenis']);
        if (isset($input['keterangan'])) $data['keterangan'] = trim($input['keterangan']);
        if (isset($input['jumlah'])) $data['jumlah'] = (int)$input['jumlah'];
        if (isset($input['sumber'])) $data['sumber'] = trim($input['sumber']);
        
        $result = updateKeuangan($id, $data);
        
        error_log("Update keuangan - Result: " . json_encode($result));
        
        if (isset($result['error'])) {
            echo json_encode(['success' => false, 'message' => 'Gagal mengupdate transaksi']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Transaksi berhasil diupdate']);
        }
        break;
    
    case 'delete':
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
            exit;
        }
        
        error_log("Delete keuangan - ID: $id");
        
        $result = deleteKeuangan($id);
        
        error_log("Delete keuangan - Result: " . json_encode($result));
        
        if (isset($result['error'])) {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus transaksi']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Transaksi berhasil dihapus']);
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

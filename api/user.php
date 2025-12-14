<?php
require_once __DIR__ . '/../config/supabase_helper.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        parse_str(file_get_contents("php://input"), $input);
        
        $data = [
            'nama' => $input['nama'] ?? '',
            'nomor' => $input['nomor'] ?? '',
            'alamat' => $input['alamat'] ?? '',
            'ktp_ktm' => $input['ktp_ktm'] ?? '',
            'email' => $input['email'] ?? '',
            'role' => $input['role'] ?? 'penyewa',
            'username' => $input['username'] ?? '',
            'password' => $input['password'] ?? '',
            'telegram_id' => $input['telegram_id'] ?? ''
        ];
        
        $result = createUser($data);
        
        if (isset($result['error'])) {
            echo json_encode(['success' => false, 'message' => 'Gagal menambah user']);
        } else {
            echo json_encode(['success' => true, 'message' => 'User berhasil ditambahkan']);
        }
        break;
    
    case 'update':
        $id = $_GET['id'] ?? null;
        parse_str(file_get_contents("php://input"), $input);
        
        if ($id && !empty($input)) {
            $result = updateUser($id, $input);
            echo json_encode(['success' => true, 'message' => 'User berhasil diupdate']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        }
        break;
    
    case 'delete':
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $result = deleteUser($id);
            header("Location: ../index.php?page=data_kos&msg=Penyewa berhasil dihapus");
        } else {
            header("Location: ../index.php?page=data_kos&error=ID tidak valid");
        }
        break;
    
    case 'get':
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $result = getUser($id);
            echo json_encode($result);
        } else {
            $result = getUser();
            echo json_encode($result);
        }
        break;
    
    default:
        echo json_encode(['error' => 'Action tidak valid']);
        break;
}

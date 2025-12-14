<?php
require_once __DIR__ . '/../config/supabase_helper.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        // Handle both POST and raw input
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            $input = $_POST;
        } else {
            parse_str(file_get_contents("php://input"), $input);
        }
        
        // Validasi input
        if (empty($input['nama']) || empty($input['email']) || empty($input['username']) || empty($input['password'])) {
            echo json_encode(['success' => false, 'message' => 'Data wajib tidak lengkap']);
            exit;
        }
        
        // Hash password menggunakan PASSWORD_DEFAULT
        $hashedPassword = password_hash($input['password'], PASSWORD_DEFAULT);
        
        $data = [
            'nama' => trim($input['nama']),
            'nomor' => trim($input['nomor'] ?? ''),
            'alamat' => trim($input['alamat'] ?? ''),
            'ktp_ktm' => trim($input['ktp_ktm'] ?? ''),
            'email' => trim($input['email']),
            'role' => trim($input['role'] ?? 'penghuni kos'),
            'username' => trim($input['username']),
            'password' => $hashedPassword,
            'telegram_id' => trim($input['telegram_id'] ?? '')
        ];
        
        $result = createUser($data);
        
        if (isset($result['error'])) {
            echo json_encode(['success' => false, 'message' => $result['message'] ?? 'Gagal menambah user']);
        } else {
            echo json_encode(['success' => true, 'message' => 'User berhasil ditambahkan']);
        }
        break;
    
    case 'update':
        $id = $_GET['id'] ?? null;
        
        // Handle both POST and raw input
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            $input = $_POST;
        } else {
            parse_str(file_get_contents("php://input"), $input);
        }
        
        if (!$id || empty($input)) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            exit;
        }
        
        // Validasi input
        if (empty($input['nama']) || empty($input['email'])) {
            echo json_encode(['success' => false, 'message' => 'Nama dan email wajib diisi']);
            exit;
        }
        
        // Prepare data untuk update
        $data = [
            'nama' => trim($input['nama']),
            'email' => trim($input['email'])
        ];
        
        // Add optional fields only if present
        if (isset($input['nomor'])) $data['nomor'] = trim($input['nomor']);
        if (isset($input['alamat'])) $data['alamat'] = trim($input['alamat']);
        if (isset($input['ktp_ktm'])) $data['ktp_ktm'] = trim($input['ktp_ktm']);
        if (isset($input['role'])) $data['role'] = trim($input['role']);
        if (isset($input['username'])) $data['username'] = trim($input['username']);
        if (isset($input['telegram_id'])) $data['telegram_id'] = trim($input['telegram_id']);
        
        // Hash password jika diisi (password baru)
        if (!empty($input['password'])) {
            $data['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
        }
        
        error_log("Update user ID: $id with data: " . json_encode($data));
        
        $result = updateUser($id, $data);
        
        if (isset($result['error'])) {
            echo json_encode(['success' => false, 'message' => $result['message'] ?? 'Gagal update user']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Data berhasil diperbarui', 'data' => $result]);
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
    
    case 'update_session':
        // Update session data after profile update
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!empty($input['nama'])) {
            $_SESSION['nama'] = $input['nama'];
        }
        if (!empty($input['email'])) {
            $_SESSION['email'] = $input['email'];
        }
        if (!empty($input['username'])) {
            $_SESSION['username'] = $input['username'];
        }
        
        echo json_encode(['success' => true, 'message' => 'Session updated']);
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

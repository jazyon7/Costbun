<?php
require_once __DIR__ . '/../config/supabase_helper.php';
session_start();

header('Content-Type: application/json');

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
        
        $fotoUrl = '';
        
        // Handle file upload if exists
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['foto'];
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $maxSize = 5 * 1024 * 1024; // 5MB
            
            // Validate file type
            if (!in_array($file['type'], $allowedTypes)) {
                echo json_encode(['success' => false, 'message' => 'Tipe file tidak diizinkan. Gunakan JPG, PNG, atau GIF']);
                exit;
            }
            
            // Validate file size
            if ($file['size'] > $maxSize) {
                echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar. Maksimal 5MB']);
                exit;
            }
            
            // Upload to Supabase Storage
            $uploadResult = uploadToSupabaseStorage($file, 'uploads', 'data_diri');
            
            if ($uploadResult['success']) {
                $fotoUrl = $uploadResult['url'];
                error_log("Foto uploaded successfully to Supabase: $fotoUrl");
            } else {
                error_log("Failed to upload foto to Supabase: " . json_encode($uploadResult));
                echo json_encode(['success' => false, 'message' => 'Gagal mengupload foto ke Supabase Storage']);
                exit;
            }
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
            'telegram_id' => trim($input['telegram_id'] ?? ''),
            'foto_url' => $fotoUrl
        ];
        
        // Add id_kamar if provided and not empty
        $id_kamar_input = null;
        if (!empty($input['id_kamar'])) {
            $data['id_kamar'] = (int)$input['id_kamar'];
            $id_kamar_input = (int)$input['id_kamar'];
        }
        
        $result = createUser($data);
        
        // Sync: Update kamar jika user dibuat dengan kamar
        if ($id_kamar_input && !isset($result['error'])) {
            // Get newly created user ID
            if (is_array($result) && isset($result[0]['id_user'])) {
                $new_user_id = $result[0]['id_user'];
                updateKamar($id_kamar_input, [
                    'id_user' => (int)$new_user_id,
                    'status' => 'terisi'
                ]);
                error_log("User created and synced to kamar $id_kamar_input");
            }
        }
        
        if (isset($result['error'])) {
            // Delete uploaded foto if database insert failed
            if ($fotoUrl && strpos($fotoUrl, 'supabase.co') !== false) {
                preg_match('/uploads\/data_diri\/(.+)$/', $fotoUrl, $matches);
                if (isset($matches[0])) {
                    deleteFromSupabaseStorage('uploads', $matches[0]);
                }
            }
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
        
        $fotoUrl = $input['foto_lama'] ?? ''; // Keep old foto by default
        
        // Handle new file upload if exists
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['foto'];
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $maxSize = 5 * 1024 * 1024; // 5MB
            
            // Validate file type
            if (!in_array($file['type'], $allowedTypes)) {
                echo json_encode(['success' => false, 'message' => 'Tipe file tidak diizinkan. Gunakan JPG, PNG, atau GIF']);
                exit;
            }
            
            // Validate file size
            if ($file['size'] > $maxSize) {
                echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar. Maksimal 5MB']);
                exit;
            }
            
            // Delete old foto from Supabase Storage if exists
            if ($fotoUrl && strpos($fotoUrl, 'supabase.co') !== false) {
                preg_match('/uploads\/data_diri\/(.+)$/', $fotoUrl, $matches);
                if (isset($matches[0])) {
                    deleteFromSupabaseStorage('uploads', $matches[0]);
                }
            }
            
            // Upload to Supabase Storage
            $uploadResult = uploadToSupabaseStorage($file, 'uploads', 'data_diri');
            
            if ($uploadResult['success']) {
                $fotoUrl = $uploadResult['url'];
                error_log("New foto uploaded successfully to Supabase: $fotoUrl");
            } else {
                error_log("Failed to upload foto to Supabase: " . json_encode($uploadResult));
                echo json_encode(['success' => false, 'message' => 'Gagal mengupload foto ke Supabase Storage']);
                exit;
            }
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
        if ($fotoUrl) $data['foto_url'] = $fotoUrl;
        
        // Handle id_kamar (can be empty to remove kamar)
        if (isset($input['id_kamar'])) {
            if (!empty($input['id_kamar'])) {
                $data['id_kamar'] = (int)$input['id_kamar'];
            } else {
                $data['id_kamar'] = null; // Set to null if empty
            }
        }
        
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
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
            exit;
        }
        
        // Get user data untuk mendapatkan id_kamar dan foto_url
        $user = getUser($id);
        
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'User tidak ditemukan']);
            exit;
        }
        
        $id_kamar = $user['id_kamar'] ?? null;
        $foto_url = $user['foto_url'] ?? '';
        
        error_log("Deleting user ID: $id, Foto: $foto_url, Kamar: $id_kamar");
        
        // STEP 1: Delete all related notifikasi (FOREIGN KEY CONSTRAINT)
        $deleteNotifResult = deleteNotifikasiByUser($id);
        error_log("Delete notifikasi for user $id: " . json_encode($deleteNotifResult));
        
        // STEP 2: Delete all related tagihan (FOREIGN KEY CONSTRAINT)
        $deleteTagihanResult = deleteTagihanByUser($id);
        error_log("Delete tagihan for user $id: " . json_encode($deleteTagihanResult));
        
        // STEP 3: Delete all related laporan (FOREIGN KEY CONSTRAINT)
        $deleteLaporanResult = deleteLaporanByUser($id);
        error_log("Delete laporan for user $id: " . json_encode($deleteLaporanResult));
        
        // STEP 4: Delete foto from Supabase Storage if exists
        if ($foto_url && strpos($foto_url, 'supabase.co') !== false) {
            preg_match('/uploads\/data_diri\/(.+)$/', $foto_url, $matches);
            if (isset($matches[0])) {
                $deleteResult = deleteFromSupabaseStorage('uploads', $matches[0]);
                error_log("Delete foto from storage: " . ($deleteResult ? 'Success' : 'Failed'));
            }
        }
        
        // STEP 5: Delete user from database
        $result = deleteUser($id);
        
        if (isset($result['error'])) {
            error_log("Delete user failed: " . json_encode($result));
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus user: ' . ($result['message'] ?? 'Unknown error')]);
            exit;
        }
        
        // STEP 6: Sync - Kosongkan kamar jika user punya kamar
        if ($id_kamar) {
            $kamarUpdate = updateKamar($id_kamar, [
                'id_user' => null,
                'status' => 'kosong'
            ]);
            error_log("Update kamar after delete: " . json_encode($kamarUpdate));
        }
        
        echo json_encode(['success' => true, 'message' => 'User berhasil dihapus beserta semua data terkait']);
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
            if ($result) {
                echo json_encode(['success' => true, 'data' => $result]);
            } else {
                echo json_encode(['success' => false, 'message' => 'User tidak ditemukan']);
            }
        } else {
            $result = getUser();
            if (is_array($result)) {
                echo json_encode(['success' => true, 'data' => $result]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal mengambil data']);
            }
        }
        break;
    
    default:
        echo json_encode(['error' => 'Action tidak valid']);
        break;
}

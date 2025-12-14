<?php
require_once __DIR__ . '/../config/supabase_helper.php';
session_start(); // Untuk akses $_SESSION

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'update_status':
        // Check role admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Akses ditolak. Hanya admin yang bisa mengubah status']);
            exit;
        }
        
        $id = $_GET['id'] ?? null;
        $status = $_GET['status'] ?? null;
        
        if ($id && $status) {
            error_log("Update laporan status - ID: $id, Status: $status");
            $result = updateLaporan($id, ['status_laporan' => $status]);
            
            if (!isset($result['error'])) {
                header("Location: ../index.php?page=laporan&msg=Status laporan berhasil diubah");
                exit;
            } else {
                header("Location: ../index.php?page=laporan&error=Gagal mengubah status");
                exit;
            }
        } else {
            header("Location: ../index.php?page=laporan&error=Data tidak lengkap");
            exit;
        }
        break;
    
    case 'create':
        // Check role penghuni
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penghuni kos') {
            echo json_encode(['success' => false, 'message' => 'Akses ditolak. Hanya penghuni yang bisa membuat laporan']);
            exit;
        }
        
        // Handle FormData (with file upload)
        $input = $_POST;
        $gambarUrl = '';
        
        error_log("Create laporan - POST data: " . json_encode($input));
        error_log("Create laporan - FILES: " . json_encode($_FILES));
        
        // Validasi required fields
        if (empty($input['judul_laporan']) || empty($input['deskripsi'])) {
            echo json_encode(['success' => false, 'message' => 'Judul dan deskripsi wajib diisi']);
            exit;
        }
        
        // Handle file upload if exists
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['gambar'];
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
            
            // Generate unique filename
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'laporan_' . time() . '_' . uniqid() . '.' . $ext;
            $uploadPath = __DIR__ . '/../img/' . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $gambarUrl = 'img/' . $filename;
                error_log("Image uploaded successfully: $gambarUrl");
            } else {
                error_log("Failed to move uploaded file");
                echo json_encode(['success' => false, 'message' => 'Gagal mengupload gambar']);
                exit;
            }
        }
        
        $data = [
            'judul_laporan' => trim($input['judul_laporan']),
            'deskripsi' => trim($input['deskripsi']),
            'status_laporan' => 'pending', // Always start with pending
            'source' => $input['source'] ?? 'web', // Source dari web app
            'gambar_url' => $gambarUrl, // URL gambar yang diupload
            'id_user' => (int)($_SESSION['id_user'] ?? 0)
        ];
        
        error_log("Create laporan - Data to save: " . json_encode($data));
        
        $result = createLaporan($data);
        
        error_log("Create laporan - Result: " . json_encode($result));
        
        if (isset($result['error'])) {
            // Delete uploaded image if database insert failed
            if ($gambarUrl && file_exists(__DIR__ . '/../' . $gambarUrl)) {
                unlink(__DIR__ . '/../' . $gambarUrl);
            }
            
            echo json_encode([
                'success' => false, 
                'message' => 'Gagal menambah laporan: ' . ($result['message'] ?? 'Unknown error'),
                'error' => $result
            ]);
        } else {
            echo json_encode([
                'success' => true, 
                'message' => 'Laporan berhasil ditambahkan',
                'data' => $result
            ]);
        }
        break;
    
    case 'update':
        // Check role penghuni
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penghuni kos') {
            echo json_encode(['success' => false, 'message' => 'Akses ditolak. Hanya penghuni yang bisa mengubah laporan']);
            exit;
        }
        
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID laporan tidak valid']);
            exit;
        }
        
        // Check ownership - pastikan laporan milik user yang login
        $existingLaporan = getLaporan($id);
        if (!$existingLaporan || $existingLaporan['id_user'] != $_SESSION['id_user']) {
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki akses untuk mengubah laporan ini']);
            exit;
        }
        
        // Handle FormData (with file upload)
        $input = $_POST;
        $gambarUrl = $input['gambar_lama'] ?? ''; // Keep old image by default
        
        error_log("Update laporan - POST data: " . json_encode($input));
        error_log("Update laporan - FILES: " . json_encode($_FILES));
        
        // Validasi required fields
        if (empty($input['judul_laporan']) || empty($input['deskripsi'])) {
            echo json_encode(['success' => false, 'message' => 'Judul dan deskripsi wajib diisi']);
            exit;
        }
        
        // Handle new file upload if exists
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['gambar'];
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
            
            // Delete old image if exists
            if ($gambarUrl && file_exists(__DIR__ . '/../' . $gambarUrl)) {
                unlink(__DIR__ . '/../' . $gambarUrl);
            }
            
            // Generate unique filename
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'laporan_' . time() . '_' . uniqid() . '.' . $ext;
            $uploadPath = __DIR__ . '/../img/' . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $gambarUrl = 'img/' . $filename;
                error_log("New image uploaded successfully: $gambarUrl");
            } else {
                error_log("Failed to move uploaded file");
                echo json_encode(['success' => false, 'message' => 'Gagal mengupload gambar']);
                exit;
            }
        }
        
        $data = [
            'judul_laporan' => trim($input['judul_laporan']),
            'deskripsi' => trim($input['deskripsi']),
            'gambar_url' => $gambarUrl
        ];
        
        error_log("Update laporan - Data to save: " . json_encode($data));
        
        $result = updateLaporan($id, $data);
        
        error_log("Update laporan - Result: " . json_encode($result));
        
        if (isset($result['error'])) {
            echo json_encode([
                'success' => false, 
                'message' => 'Gagal memperbarui laporan: ' . ($result['message'] ?? 'Unknown error')
            ]);
        } else {
            echo json_encode([
                'success' => true, 
                'message' => 'Laporan berhasil diperbarui',
                'data' => $result
            ]);
        }
        break;
    
    case 'delete':
        // Check role penghuni
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penghuni kos') {
            echo json_encode(['success' => false, 'message' => 'Akses ditolak. Hanya penghuni yang bisa menghapus laporan']);
            exit;
        }
        
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID laporan tidak valid']);
            exit;
        }
        
        // Check ownership - pastikan laporan milik user yang login
        $existingLaporan = getLaporan($id);
        if (!$existingLaporan || $existingLaporan['id_user'] != $_SESSION['id_user']) {
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki akses untuk menghapus laporan ini']);
            exit;
        }
        
        // Delete image file if exists
        if (!empty($existingLaporan['gambar_url'])) {
            $imagePath = __DIR__ . '/../' . $existingLaporan['gambar_url'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
                error_log("Deleted image file: " . $existingLaporan['gambar_url']);
            }
        }
        
        // Delete from database
        $result = deleteLaporan($id);
        
        if (isset($result['error'])) {
            echo json_encode([
                'success' => false, 
                'message' => 'Gagal menghapus laporan: ' . ($result['message'] ?? 'Unknown error')
            ]);
        } else {
            echo json_encode([
                'success' => true, 
                'message' => 'Laporan berhasil dihapus'
            ]);
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

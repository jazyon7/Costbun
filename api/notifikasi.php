<?php
require_once __DIR__ . '/../config/supabase_helper.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        // Parse form data from FormData
        $input = $_POST;
        
        // Base notification data
        $baseData = [
            'tipe' => trim($input['tipe'] ?? ''),
            'judul' => trim($input['judul'] ?? ''),
            'pesan' => trim($input['pesan'] ?? ''),
            'tanggal_kirim' => $input['tanggal_kirim'] ?? date('Y-m-d'),
            'status' => 'unread',
            'dikirim_n8n' => 'false'
        ];
        
        $sendTo = $input['send_to'] ?? 'all';
        $successCount = 0;
        $errorCount = 0;
        
        // Broadcast logic
        if ($sendTo === 'all') {
            // Get all penghuni kos users
            $users = getUser(); // Get all users
            
            foreach ($users as $user) {
                // Skip admin users
                if ($user['role'] === 'penghuni kos') {
                    $notifData = $baseData;
                    $notifData['id_user'] = (int)$user['id_user'];
                    
                    $result = createNotifikasi($notifData);
                    
                    if (isset($result['error'])) {
                        $errorCount++;
                    } else {
                        $successCount++;
                    }
                }
            }
            
            if ($successCount > 0) {
                echo json_encode([
                    'success' => true, 
                    'message' => "Notifikasi berhasil dikirim ke $successCount penghuni"
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Gagal mengirim notifikasi'
                ]);
            }
            
        } elseif ($sendTo === 'specific') {
            // Send to specific users
            $selectedUsers = $input['id_user'] ?? [];
            
            if (empty($selectedUsers)) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Pilih minimal 1 penghuni'
                ]);
                break;
            }
            
            // Ensure it's an array
            if (!is_array($selectedUsers)) {
                $selectedUsers = [$selectedUsers];
            }
            
            foreach ($selectedUsers as $userId) {
                $notifData = $baseData;
                $notifData['id_user'] = (int)$userId;
                
                $result = createNotifikasi($notifData);
                
                if (isset($result['error'])) {
                    $errorCount++;
                } else {
                    $successCount++;
                }
            }
            
            if ($successCount > 0) {
                echo json_encode([
                    'success' => true, 
                    'message' => "Notifikasi berhasil dikirim ke $successCount penghuni"
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Gagal mengirim notifikasi'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Parameter send_to tidak valid'
            ]);
        }
        break;
    
    case 'update':
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id_notif'] ?? null;
        
        if ($id) {
            $updateData = [];
            if (isset($input['status'])) {
                $updateData['status'] = $input['status'];
            }
            if (isset($input['tipe'])) {
                $updateData['tipe'] = $input['tipe'];
            }
            if (isset($input['judul'])) {
                $updateData['judul'] = $input['judul'];
            }
            if (isset($input['pesan'])) {
                $updateData['pesan'] = $input['pesan'];
            }
            
            $result = updateNotifikasi($id, $updateData);
            
            if (isset($result['error'])) {
                echo json_encode(['success' => false, 'message' => 'Gagal update notifikasi']);
            } else {
                echo json_encode(['success' => true, 'message' => 'Notifikasi berhasil diupdate']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
        }
        break;
    
    case 'delete':
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id_notif'] ?? null;
        
        if ($id) {
            $result = deleteNotifikasi($id);
            
            if (isset($result['error'])) {
                echo json_encode(['success' => false, 'message' => 'Gagal menghapus notifikasi']);
            } else {
                echo json_encode(['success' => true, 'message' => 'Notifikasi berhasil dihapus']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
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

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
    
    case 'assign_penghuni':
        // Get POST data
        $rawInput = file_get_contents("php://input");
        parse_str($rawInput, $input);
        
        // Log untuk debugging
        error_log("=== ASSIGN PENGHUNI START ===");
        error_log("Assign Penghuni - Raw input: " . $rawInput);
        error_log("Assign Penghuni - Parsed input: " . print_r($input, true));
        
        $id_kamar = $input['id_kamar'] ?? null;
        $id_user = $input['id_user'] ?? null;
        
        error_log("Assign Penghuni - id_kamar: $id_kamar, id_user: $id_user");
        
        if (!$id_kamar || !$id_user) {
            $response = [
                'success' => false, 
                'message' => 'Data tidak lengkap (id_kamar: ' . ($id_kamar ? 'OK' : 'NULL') . ', id_user: ' . ($id_user ? 'OK' : 'NULL') . ')'
            ];
            error_log("Assign Penghuni - FAILED: " . json_encode($response));
            echo json_encode($response);
            exit;
        }
        
        // Step 1: Update kamar dengan id_user dan status terisi
        $data = [
            'id_user' => (int)$id_user,
            'status' => 'terisi'
        ];
        
        error_log("Assign Penghuni - Updating kamar $id_kamar with data: " . json_encode($data));
        
        $result = updateKamar($id_kamar, $data);
        
        // Step 2: Update user dengan id_kamar (SINKRONISASI)
        error_log("Assign Penghuni - Updating user $id_user with id_kamar=$id_kamar");
        $userUpdateResult = supabase_request('PATCH', "/rest/v1/user?id_user=eq.$id_user", [
            'id_kamar' => (int)$id_kamar
        ]);
        error_log("Assign Penghuni - User update result: " . json_encode($userUpdateResult));
        
        error_log("Assign Penghuni - Update result: " . json_encode($result));
        
        // Check if update was successful
        // Supabase might return array with data, or ['success' => true], or empty array
        if (isset($result['error']) && $result['error'] === true) {
            $response = [
                'success' => false, 
                'message' => 'Gagal assign penghuni: ' . ($result['message'] ?? 'Unknown error'),
                'details' => $result
            ];
            error_log("Assign Penghuni - ERROR: " . json_encode($response));
            echo json_encode($response);
        } else {
            // Verify the update by fetching the kamar
            $verifyKamar = getKamar($id_kamar);
            error_log("Assign Penghuni - Verify kamar after update: " . json_encode($verifyKamar));
            
            if ($verifyKamar && $verifyKamar['id_user'] == $id_user) {
                $response = [
                    'success' => true, 
                    'message' => 'Penghuni berhasil di-assign ke kamar',
                    'verified' => true,
                    'kamar' => $verifyKamar
                ];
                error_log("Assign Penghuni - SUCCESS & VERIFIED: " . json_encode($response));
            } else {
                $response = [
                    'success' => true, 
                    'message' => 'API response OK, tapi data belum ter-update. Coba refresh halaman.',
                    'verified' => false,
                    'kamar' => $verifyKamar
                ];
                error_log("Assign Penghuni - WARNING: Update OK but not verified");
            }
            
            echo json_encode($response);
        }
        error_log("=== ASSIGN PENGHUNI END ===");
        break;
    
    case 'remove_penghuni':
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header("Location: ../index.php?page=data_kamar&error=ID tidak valid");
            exit;
        }
        
        // Get kamar data untuk mendapatkan id_user
        $kamar = getKamar($id);
        $id_user = $kamar['id_user'] ?? null;
        
        // Update kamar: hapus id_user dan ubah status jadi kosong
        $data = [
            'id_user' => null,
            'status' => 'kosong'
        ];
        
        $result = updateKamar($id, $data);
        
        // Sync: Update user untuk menghapus id_kamar
        if ($id_user) {
            supabase_request('PATCH', "/rest/v1/user?id_user=eq.$id_user", [
                'id_kamar' => null
            ]);
        }
        
        if (isset($result['error'])) {
            header("Location: ../index.php?page=data_kamar&error=Gagal mengosongkan kamar");
        } else {
            header("Location: ../index.php?page=data_kamar&msg=Kamar berhasil dikosongkan");
        }
        break;
    
    case 'create_kamar':
        parse_str(file_get_contents("php://input"), $input);
        
        $data = [
            'nama' => $input['nama'] ?? '',
            'kasur' => (int)($input['kasur'] ?? 1),
            'kipas' => (int)($input['kipas'] ?? 1),
            'lemari' => (int)($input['lemari'] ?? 1),
            'keranjang_sampah' => (int)($input['keranjang_sampah'] ?? 1),
            'ac' => (int)($input['ac'] ?? 0),
            'harga' => (int)($input['harga'] ?? 0),
            'status' => 'kosong',
            'id_user' => null
        ];
        
        $result = createKamar($data);
        
        if (isset($result['error'])) {
            echo json_encode(['success' => false, 'message' => 'Gagal menambah kamar']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Kamar berhasil ditambahkan']);
        }
        break;
    
    default:
        echo json_encode(['error' => 'Action tidak valid']);
        break;
}

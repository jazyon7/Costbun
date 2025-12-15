<?php
require_once 'supabase_request.php';

// ==================== KAMAR ====================
function getKamar($id_kamar = null) {
    if ($id_kamar) {
        $response = supabase_request('GET', "/rest/v1/kamar?id_kamar=eq.$id_kamar");
        return !empty($response) ? $response[0] : null;
    }
    return supabase_request('GET', '/rest/v1/kamar?order=id_kamar.asc');
}

function createKamar($data) {
    return supabase_request('POST', '/rest/v1/kamar', $data);
}

function updateKamar($id_kamar, $data) {
    error_log("updateKamar() - ID: $id_kamar, Data: " . json_encode($data));
    $result = supabase_request('PATCH', "/rest/v1/kamar?id_kamar=eq.$id_kamar", $data);
    error_log("updateKamar() - Result: " . json_encode($result));
    return $result;
}

function deleteKamar($id_kamar) {
    return supabase_request('DELETE', "/rest/v1/kamar?id_kamar=eq.$id_kamar");
}

// ==================== USER ====================
function getUser($id_user = null) {
    if ($id_user) {
        // Ambil data user tanpa JOIN untuk menghindari circular reference
        $response = supabase_request('GET', "/rest/v1/user?id_user=eq.$id_user");
        if (!empty($response) && isset($response[0])) {
            $user = $response[0];
            // Ambil nama kamar secara manual jika ada id_kamar
            if (!empty($user['id_kamar'])) {
                $kamar = getKamar($user['id_kamar']);
                $user['kamar_nama'] = $kamar ? $kamar['nama'] : null;
            } else {
                $user['kamar_nama'] = null;
            }
            return $user;
        }
        return null;
    }
    // Get all users tanpa JOIN
    return supabase_request('GET', '/rest/v1/user?order=id_user.asc');
}

function createUser($data) {
    return supabase_request('POST', '/rest/v1/user', $data);
}

function updateUser($id_user, $data) {
    // Jika ada perubahan id_kamar, sync dengan table kamar
    if (array_key_exists('id_kamar', $data)) {
        $oldUser = getUser($id_user);
        $oldKamarId = $oldUser['id_kamar'] ?? null;
        $newKamarId = $data['id_kamar'];
        
        // Jika user pindah dari kamar lama
        if ($oldKamarId && $oldKamarId != $newKamarId) {
            // Kosongkan kamar lama
            updateKamar($oldKamarId, [
                'id_user' => null,
                'status' => 'kosong'
            ]);
        }
        
        // Jika user dapat kamar baru
        if ($newKamarId) {
            // Set kamar baru sebagai terisi
            updateKamar($newKamarId, [
                'id_user' => (int)$id_user,
                'status' => 'terisi'
            ]);
        }
    }
    
    return supabase_request('PATCH', "/rest/v1/user?id_user=eq.$id_user", $data);
}

function deleteUser($id_user) {
    return supabase_request('DELETE', "/rest/v1/user?id_user=eq.$id_user");
}

// ==================== LAPORAN ====================
function getLaporan($id_laporan = null) {
    if ($id_laporan) {
        $response = supabase_request('GET', "/rest/v1/laporan?id_laporan=eq.$id_laporan&select=*,user(nama)");
        return !empty($response) ? $response[0] : null;
    }
    return supabase_request('GET', '/rest/v1/laporan?select=*,user(nama)&order=created_at.desc');
}

function createLaporan($data) {
    return supabase_request('POST', '/rest/v1/laporan', $data);
}

function updateLaporan($id_laporan, $data) {
    return supabase_request('PATCH', "/rest/v1/laporan?id_laporan=eq.$id_laporan", $data);
}

function deleteLaporan($id_laporan) {
    return supabase_request('DELETE', "/rest/v1/laporan?id_laporan=eq.$id_laporan");
}

function deleteLaporanByUser($id_user) {
    return supabase_request('DELETE', "/rest/v1/laporan?id_user=eq.$id_user");
}

// ==================== NOTIFIKASI ====================
function getNotifikasi($id_notif = null) {
    if ($id_notif) {
        $response = supabase_request('GET', "/rest/v1/notifikasi?id_notif=eq.$id_notif&select=*,user(nama)");
        return !empty($response) ? $response[0] : null;
    }
    $response = supabase_request('GET', '/rest/v1/notifikasi?select=*,user(nama)&order=id_notif.desc');
    return is_array($response) ? $response : [];
}

function createNotifikasi($data) {
    return supabase_request('POST', '/rest/v1/notifikasi', $data);
}

function updateNotifikasi($id_notif, $data) {
    return supabase_request('PATCH', "/rest/v1/notifikasi?id_notif=eq.$id_notif", $data);
}

function deleteNotifikasi($id_notif) {
    return supabase_request('DELETE', "/rest/v1/notifikasi?id_notif=eq.$id_notif");
}

function deleteNotifikasiByUser($id_user) {
    return supabase_request('DELETE', "/rest/v1/notifikasi?id_user=eq.$id_user");
}

// ==================== TAGIHAN ====================
function getTagihan($id_tagihan = null) {
    if ($id_tagihan) {
        $response = supabase_request('GET', "/rest/v1/tagihan?id_tagihan=eq.$id_tagihan&select=*,user(nama),kamar(nama)");
        return !empty($response) ? $response[0] : null;
    }
    return supabase_request('GET', '/rest/v1/tagihan?select=*,user(nama),kamar(nama)&order=tgl_tagihan.desc');
}

function createTagihan($data) {
    return supabase_request('POST', '/rest/v1/tagihan', $data);
}

function updateTagihan($id_tagihan, $data) {
    return supabase_request('PATCH', "/rest/v1/tagihan?id_tagihan=eq.$id_tagihan", $data);
}

function deleteTagihan($id_tagihan) {
    return supabase_request('DELETE', "/rest/v1/tagihan?id_tagihan=eq.$id_tagihan");
}

function deleteTagihanByUser($id_user) {
    return supabase_request('DELETE', "/rest/v1/tagihan?id_user=eq.$id_user");
}

function deleteTagihanByKamar($id_kamar) {
    return supabase_request('DELETE', "/rest/v1/tagihan?id_kamar=eq.$id_kamar");
}

// ==================== KEUANGAN ====================
function getKeuangan($id_keuangan = null) {
    if ($id_keuangan) {
        $response = supabase_request('GET', "/rest/v1/keuangan?id_keuangan=eq.$id_keuangan");
        return !empty($response) ? $response[0] : null;
    }
    return supabase_request('GET', '/rest/v1/keuangan?order=tanggal_tranksaksi.desc');
}

function createKeuangan($data) {
    return supabase_request('POST', '/rest/v1/keuangan', $data);
}

function updateKeuangan($id_keuangan, $data) {
    return supabase_request('PATCH', "/rest/v1/keuangan?id_keuangan=eq.$id_keuangan", $data);
}

function deleteKeuangan($id_keuangan) {
    return supabase_request('DELETE', "/rest/v1/keuangan?id_keuangan=eq.$id_keuangan");
}

// ==================== STORAGE ====================
function uploadToSupabaseStorage($file, $bucket, $folder = '') {
    require_once 'supabase.php';
    
    // Generate unique filename
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = time() . '.' . $ext;
    $path = $folder ? "$folder/$filename" : $filename;
    
    // Read file content
    $fileContent = file_get_contents($file['tmp_name']);
    
    // Get MIME type
    $mimeType = $file['type'];
    
    // Upload to Supabase Storage
    $url = SUPABASE_URL . "/storage/v1/object/$bucket/$path";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . SUPABASE_API_KEY,
        'Content-Type: ' . $mimeType,
        'x-upsert: true' // Allow overwrite if file exists
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 || $httpCode === 201) {
        // Return public URL
        return [
            'success' => true,
            'url' => SUPABASE_URL . "/storage/v1/object/public/$bucket/$path",
            'path' => $path
        ];
    } else {
        error_log("Upload failed - HTTP $httpCode: $response");
        return [
            'success' => false,
            'error' => $response
        ];
    }
}

function deleteFromSupabaseStorage($bucket, $path) {
    require_once 'supabase.php';
    
    $url = SUPABASE_URL . "/storage/v1/object/$bucket/$path";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . SUPABASE_API_KEY
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 200;
}

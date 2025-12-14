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
        $response = supabase_request('GET', "/rest/v1/user?id_user=eq.$id_user");
        return !empty($response) ? $response[0] : null;
    }
    return supabase_request('GET', '/rest/v1/user?order=id_user.asc');
}

function createUser($data) {
    return supabase_request('POST', '/rest/v1/user', $data);
}

function updateUser($id_user, $data) {
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

<?php
/**
 * Form Handler - Process form submissions untuk semua tabel
 */

require_once 'config/supabase_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: form_tambah_data.php?error=Invalid request method");
    exit;
}

$table = $_POST['table'] ?? '';

switch ($table) {
    case 'user':
        // Hash password untuk keamanan
        $password = $_POST['password'] ?? '';
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : '-';
        
        $data = [
            'nama' => $_POST['nama'] ?? '',
            'nomor' => $_POST['nomor'] ?? '',
            'alamat' => $_POST['alamat'] ?? '',
            'ktp_ktm' => $_POST['ktp_ktm'] ?? '',
            'email' => $_POST['email'] ?? '',
            'role' => $_POST['role'] ?? 'penghuni kos',
            'username' => $_POST['username'] ?? '',
            'password' => $hashedPassword,
            'telegram_id' => $_POST['telegram_id'] ?? ''
        ];
        
        // Tambahkan id_kamar jika dipilih
        $id_kamar_input = null;
        if (!empty($_POST['id_kamar'])) {
            $data['id_kamar'] = (int)$_POST['id_kamar'];
            $id_kamar_input = (int)$_POST['id_kamar'];
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
            }
        }
        
        if (isset($result['error'])) {
            header("Location: index.php?page=tambah_data&error=Gagal menambah user: " . urlencode($result['message'] ?? 'Unknown error'));
        } else {
            header("Location: index.php?page=tambah_data&msg=User berhasil ditambahkan" . ($id_kamar_input ? " dan di-assign ke kamar" : ""));
        }
        break;
    
    case 'laporan':
        $data = [
            'judul_laporan' => $_POST['judul_laporan'] ?? '',
            'deskripsi' => $_POST['deskripsi'] ?? '',
            'status_laporan' => $_POST['status_laporan'] ?? 'diproses',
            'source' => $_POST['source'] ?? 'web',
            'gambar_url' => $_POST['gambar_url'] ?? '',
            'id_user' => (int)($_POST['id_user'] ?? 0)
        ];
        
        $result = createLaporan($data);
        
        if (isset($result['error'])) {
            header("Location: form_tambah_data.php?error=Gagal menambah laporan");
        } else {
            header("Location: form_tambah_data.php?msg=Laporan berhasil ditambahkan");
        }
        break;
    
    case 'notifikasi':
        $data = [
            'tipe' => $_POST['tipe'] ?? '',
            'judul' => $_POST['judul'] ?? '',
            'pesan' => $_POST['pesan'] ?? '',
            'tanggal_kirim' => $_POST['tanggal_kirim'] ?? date('Y-m-d'),
            'status' => $_POST['status'] ?? 'unread',
            'dikirim_n8n' => $_POST['dikirim_n8n'] ?? 'false',
            'id_user' => (int)($_POST['id_user'] ?? 0)
        ];
        
        $result = createNotifikasi($data);
        
        if (isset($result['error'])) {
            header("Location: form_tambah_data.php?error=Gagal menambah notifikasi");
        } else {
            header("Location: form_tambah_data.php?msg=Notifikasi berhasil ditambahkan");
        }
        break;
    
    case 'tagihan':
        $data = [
            'jumlah' => (int)($_POST['jumlah'] ?? 0),
            'tgl_tagihan' => $_POST['tgl_tagihan'] ?? date('Y-m-d'),
            'tgl_tempo' => $_POST['tgl_tempo'] ?? date('Y-m-d'),
            'status_pembayaran' => $_POST['status_pembayaran'] ?? 'belum_lunas',
            'metode_pembayaran' => $_POST['metode_pembayaran'] ?? '',
            'bukti_pembayaran' => $_POST['bukti_pembayaran'] ?? '',
            'id_user' => (int)($_POST['id_user'] ?? 0),
            'id_kamar' => (int)($_POST['id_kamar'] ?? 0)
        ];
        
        $result = createTagihan($data);
        
        if (isset($result['error'])) {
            header("Location: form_tambah_data.php?error=Gagal menambah tagihan");
        } else {
            header("Location: form_tambah_data.php?msg=Tagihan berhasil ditambahkan");
        }
        break;
    
    case 'keuangan':
        $data = [
            'tanggal_tranksaksi' => $_POST['tanggal_tranksaksi'] ?? date('Y-m-d'),
            'jenis' => $_POST['jenis'] ?? '',
            'keterangan' => $_POST['keterangan'] ?? '',
            'jumlah' => (int)($_POST['jumlah'] ?? 0),
            'sumber' => $_POST['sumber'] ?? ''
        ];
        
        $result = createKeuangan($data);
        
        if (isset($result['error'])) {
            header("Location: form_tambah_data.php?error=Gagal menambah transaksi keuangan");
        } else {
            header("Location: form_tambah_data.php?msg=Transaksi keuangan berhasil ditambahkan");
        }
        break;
    
    default:
        header("Location: form_tambah_data.php?error=Tabel tidak valid");
        break;
}

exit;

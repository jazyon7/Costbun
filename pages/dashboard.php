<?php
require_once __DIR__ . '/../config/supabase_helper.php';

// Get statistik dari Supabase
$kamarList = getKamar();
$userList = getUser();
$laporanList = getLaporan();
$notifikasiList = getNotifikasi();

// Debug logging
error_log("Dashboard - Kamar count: " . (is_array($kamarList) ? count($kamarList) : 0));
error_log("Dashboard - User count: " . (is_array($userList) ? count($userList) : 0));
error_log("Dashboard - Laporan count: " . (is_array($laporanList) ? count($laporanList) : 0));
error_log("Dashboard - Notifikasi count: " . (is_array($notifikasiList) ? count($notifikasiList) : 0));

// Hitung statistik KAMAR
$totalKamar = is_array($kamarList) ? count($kamarList) : 0;
$kamarTerisi = 0;
if (is_array($kamarList)) {
    foreach ($kamarList as $kamar) {
        $status = strtolower(trim($kamar['status'] ?? ''));
        if ($status === 'terisi') {
            $kamarTerisi++;
        }
    }
}
$kamarKosong = $totalKamar - $kamarTerisi;

// Hitung statistik USER
$totalUser = is_array($userList) ? count($userList) : 0;
$totalPenyewa = 0;
$totalAdmin = 0;
if (is_array($userList)) {
    foreach ($userList as $user) {
        $role = strtolower(trim($user['role'] ?? ''));
        // Support both 'penghuni kos' and 'penyewa'
        if ($role === 'penghuni kos' || $role === 'penyewa') {
            $totalPenyewa++;
        } elseif ($role === 'admin') {
            $totalAdmin++;
        }
    }
}

// Hitung statistik LAPORAN
$totalLaporan = is_array($laporanList) ? count($laporanList) : 0;
$laporanPending = 0;
$laporanProses = 0;
$laporanSelesai = 0;
if (is_array($laporanList)) {
    foreach ($laporanList as $laporan) {
        $status = strtolower(trim($laporan['status_laporan'] ?? ''));
        if ($status === 'pending') {
            $laporanPending++;
        } elseif ($status === 'diproses') {
            $laporanProses++;
        } elseif ($status === 'selesai') {
            $laporanSelesai++;
        }
    }
}

// Hitung statistik NOTIFIKASI
$totalNotif = is_array($notifikasiList) ? count($notifikasiList) : 0;
$notifUnread = 0;
if (is_array($notifikasiList)) {
    foreach ($notifikasiList as $notif) {
        $status = strtolower(trim($notif['status'] ?? ''));
        if ($status === 'unread') {
            $notifUnread++;
        }
    }
}

// Debug hasil perhitungan
error_log("Dashboard Stats - Kamar: Total=$totalKamar, Terisi=$kamarTerisi, Kosong=$kamarKosong");
error_log("Dashboard Stats - User: Total=$totalUser, Penyewa=$totalPenyewa, Admin=$totalAdmin");
error_log("Dashboard Stats - Laporan: Total=$totalLaporan, Pending=$laporanPending, Proses=$laporanProses, Selesai=$laporanSelesai");
error_log("Dashboard Stats - Notifikasi: Total=$totalNotif, Unread=$notifUnread");
?>

<style>
  .dashboard-container {
    max-height: calc(100vh - 150px);
    overflow-y: auto;
    padding-right: 10px;
  }
  
  .dashboard-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 20px;
  }
  
  @media (max-width: 1200px) {
    .dashboard-cards {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  
  @media (max-width: 768px) {
    .dashboard-cards {
      grid-template-columns: 1fr;
    }
  }
  
  /* Scrollbar styling */
  .dashboard-container::-webkit-scrollbar {
    width: 8px;
  }
  
  .dashboard-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
  }
  
  .dashboard-container::-webkit-scrollbar-thumb {
    background: #3681ff;
    border-radius: 10px;
  }
  
  .dashboard-container::-webkit-scrollbar-thumb:hover {
    background: #2d6ad4;
  }
</style>

<div class="dashboard-container">
  <section class="dashboard-cards">
    <!-- Card 1: Total Kamar -->
    <div class="card card-blue">
      <div class="card-icon">
        <i class="fa-solid fa-door-closed" style="font-size: 48px; color: #3681ff;"></i>
      </div>
      <h3>Total Kamar</h3>
      <p class="card-desc"><?= $kamarKosong ?> Kosong / <?= $kamarTerisi ?> Terisi</p>
      <div class="card-status">
        <span class="card-status-title"><?= $totalKamar ?> Kamar</span>
        <a href="index.php?page=data_kamar" style="color: #3681ff; text-decoration: none;">
          <span class="card-status-switch on">Lihat Detail →</span>
        </a>
      </div>
    </div>

    <!-- Card 2: Penyewa Aktif -->
    <div class="card card-purple">
      <div class="card-icon">
        <i class="fa-solid fa-users" style="font-size: 48px; color: #764ba2;"></i>
      </div>
      <h3>Penyewa Aktif</h3>
      <p class="card-desc">Total <?= $totalUser ?> User Terdaftar</p>
      <div class="card-status">
        <span class="card-status-title"><?= $totalPenyewa ?> Penyewa</span>
        <a href="index.php?page=data_kos" style="color: #764ba2; text-decoration: none;">
          <span class="card-status-switch on">Lihat Detail →</span>
        </a>
      </div>
    </div>

    <!-- Card 3: Laporan -->
    <div class="card card-yellow">
      <div class="card-icon">
        <i class="fa-solid fa-file-lines" style="font-size: 48px; color: #ffa726;"></i>
      </div>
      <h3>Laporan</h3>
      <p class="card-desc"><?= $laporanProses ?> Sedang Diproses</p>
      <div class="card-status">
        <span class="card-status-title"><?= $totalLaporan ?> Total</span>
        <a href="index.php?page=laporan" style="color: #ffa726; text-decoration: none;">
          <span class="card-status-switch on">Lihat Detail →</span>
        </a>
      </div>
    </div>

    <!-- Card 4: Notifikasi -->
    <div class="card card-green">
      <div class="card-icon">
        <i class="fa-solid fa-bell" style="font-size: 48px; color: #4CAF50;"></i>
      </div>
      <h3>Notifikasi</h3>
      <p class="card-desc"><?= $notifUnread ?> Belum Dibaca</p>
      <div class="card-status">
        <span class="card-status-title"><?= $totalNotif ?> Total</span>
        <a href="index.php?page=notifikasi" style="color: #4CAF50; text-decoration: none;">
          <span class="card-status-switch on">Lihat Detail →</span>
        </a>
      </div>
    </div>
    
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): 
      // Get Keuangan statistics for admin only
      $keuanganList = getKeuangan();
      $totalPemasukan = 0;
      $totalPengeluaran = 0;
      
      if (is_array($keuanganList)) {
          foreach ($keuanganList as $k) {
              $jenis = strtolower(trim($k['jenis'] ?? ''));
              $jumlah = floatval($k['jumlah'] ?? 0);
              
              if ($jenis === 'pemasukan') {
                  $totalPemasukan += $jumlah;
              } elseif ($jenis === 'pengeluaran') {
                  $totalPengeluaran += $jumlah;
              }
          }
      }
      $saldo = $totalPemasukan - $totalPengeluaran;
    ?>
    
    <!-- Card 5: Keuangan (Admin Only) -->
    <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
      <div class="card-icon">
        <i class="fa-solid fa-money-bill-wave" style="font-size: 48px; color: white;"></i>
      </div>
      <h3 style="color: white;">Keuangan</h3>
      <p class="card-desc" style="color: rgba(255,255,255,0.9);">
        Saldo: Rp <?= number_format($saldo, 0, ',', '.') ?>
      </p>
      <div class="card-status">
        <span class="card-status-title" style="color: white;">
          <?= is_array($keuanganList) ? count($keuanganList) : 0 ?> Transaksi
        </span>
        <a href="index.php?page=keuangan" style="color: white; text-decoration: none;">
          <span class="card-status-switch on" style="color: white;">Lihat Detail →</span>
        </a>
      </div>
    </div>
    
    <?php endif; ?>
    
    <!-- Card 6: Info User -->
    <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
      <div class="card-icon">
        <i class="fa-solid fa-user-circle" style="font-size: 48px; color: white;"></i>
      </div>
      <h3 style="color: white;">Profil Saya</h3>
      <p class="card-desc" style="color: rgba(255,255,255,0.9);">
        <?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?>
      </p>
      <div class="card-status">
        <span class="card-status-title" style="color: white;">
          <?= htmlspecialchars($_SESSION['role'] ?? 'Role') ?>
        </span>
        <a href="index.php?page=profile" style="color: white; text-decoration: none;">
          <span class="card-status-switch on" style="color: white;">Edit Profile →</span>
        </a>
      </div>
    </div>
    
  </section>
  
  <!-- Quick Stats Summary -->
  <section style="margin-top: 20px; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
    <h3 style="margin: 0 0 15px 0; color: #333; font-size: 18px;">
      <i class="fa-solid fa-chart-line" style="color: #3681ff;"></i> Ringkasan Statistik
    </h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
      <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #3681ff;">
        <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Tingkat Hunian</div>
        <div style="font-size: 20px; font-weight: bold; color: #3681ff;">
          <?= $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100) : 0 ?>%
        </div>
      </div>
      
      <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #764ba2;">
        <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Total Pengguna</div>
        <div style="font-size: 20px; font-weight: bold; color: #764ba2;">
          <?= $totalUser ?> User
        </div>
      </div>
      
      <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #ffa726;">
        <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Laporan Pending</div>
        <div style="font-size: 20px; font-weight: bold; color: #ffa726;">
          <?= $laporanPending ?> Laporan
        </div>
      </div>
      
      <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #4CAF50;">
        <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Notifikasi Unread</div>
        <div style="font-size: 20px; font-weight: bold; color: #4CAF50;">
          <?= $notifUnread ?> Notif
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  console.log('Dashboard Stats:', {
    kamar: { total: <?= $totalKamar ?>, terisi: <?= $kamarTerisi ?>, kosong: <?= $kamarKosong ?> },
    user: { total: <?= $totalUser ?>, penyewa: <?= $totalPenyewa ?> },
    laporan: { total: <?= $totalLaporan ?>, pending: <?= $laporanPending ?>, proses: <?= $laporanProses ?>, selesai: <?= $laporanSelesai ?> },
    notifikasi: { total: <?= $totalNotif ?>, unread: <?= $notifUnread ?> }
  });
</script>

<?php
require_once __DIR__ . '/../config/supabase_helper.php';

// Get statistik dari Supabase
$kamarList = getKamar();
$userList = getUser();
$laporanList = getLaporan();
$notifikasiList = getNotifikasi();

// Hitung statistik
$totalKamar = is_array($kamarList) ? count($kamarList) : 0;
$kamarTerisi = is_array($kamarList) ? count(array_filter($kamarList, fn($k) => strtolower($k['status']) == 'terisi')) : 0;
$kamarKosong = $totalKamar - $kamarTerisi;

$totalUser = is_array($userList) ? count($userList) : 0;
$totalPenyewa = is_array($userList) ? count(array_filter($userList, fn($u) => strtolower($u['role']) == 'penyewa')) : 0;

$totalLaporan = is_array($laporanList) ? count($laporanList) : 0;
$laporanProses = is_array($laporanList) ? count(array_filter($laporanList, fn($l) => strtolower($l['status_laporan']) == 'diproses')) : 0;

$totalNotif = is_array($notifikasiList) ? count($notifikasiList) : 0;
$notifUnread = is_array($notifikasiList) ? count(array_filter($notifikasiList, fn($n) => strtolower($n['status']) == 'unread')) : 0;
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
  </section>
</div>

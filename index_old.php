<?php

session_start();
if(!isset($_SESSION['id_user'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin Kos</title>
  <link rel="stylesheet" href="style.css">
  <script src="navigasi.js"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
</head>

<body>
  <div class="container">

    <!-- ================= SIDEBAR ================= -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <i class="fa-solid fa-house-signal"></i>
        <div class="sidebar-dots">
          <span></span><span></span><span></span>
        </div>
      </div>

      <nav class="sidebar-menu">
        <a href="index.php?page=dashboard"><i class="fa fa-th-large"></i> Dashboard</a>
        <a href="index.php?page=notifikasi"><i class="fa-regular fa-bell"></i> Notifikasi</a>
        <a href="index.php?page=data_kos"><i class="fa fa-clipboard"></i> Data Kos</a>
        <a href="index.php?page=data_kamar"><i class="fa-solid fa-door-closed"></i> Data Kamar</a>
        <a href="index.php?page=laporan"><i class="fa-solid fa-file-lines"></i> Laporan</a>
        <hr>
        <a href="index.php?page=tools"><i class="fa-solid fa-screwdriver-wrench"></i> Tools</a>
        <a href="index.php?page=test_koneksi"><i class="fa-solid fa-plug"></i> Test Koneksi</a>
        <a href="index.php?page=tambah_data"><i class="fa-solid fa-plus-circle"></i> Tambah Data</a>
        <a href="index.php?page=demo_api"><i class="fa-solid fa-flask"></i> Demo API</a>
        <hr>
        <a href="index.php?page=profile"><i class="fa fa-user"></i> My Profile</a>
        <a href="index.php?page=setting"><i class="fa-solid fa-gear"></i> Settings</a>
        <a href="index.php?page=logout"><i class="fa-solid fa-right-from-bracket"></i> Sign Out</a>

        <div class="sidebar-help">
          <a href="index.php?page=help"><i class="fa-regular fa-circle-question"></i> Help</a>
        </div>
      </nav>
    </aside>
    <!-- ================= END SIDEBAR ================= -->

    <!-- ================= MAIN ================= -->
    <main>
      <header class="main-header">
        <div>
          <h2>
            <?php 
              echo isset($_GET['page']) 
                ? ucfirst($_GET['page']) 
                : "Dashboard"; 
            ?>
          </h2>
          <p>Kos Bu Anna!</p>
        </div>
      </header>

      <!-- ==== ROUTER ==== -->
      <section class="content">
        <?php
          // Jika page ada
          if(isset($_GET['page'])) {
              $page = $_GET['page'];

              switch($page) {

                  // ✅ Dashboard
                  case 'dashboard':
                      include "pages/dashboard.php";
                      break;

                  case 'notifikasi':
                       include "pages/notifikasi.php";
                      break;

                  case 'data_kos':
                       include "pages/data_kost.php";
                      break;

                  case 'data_kamar':
                      include "pages/data_kamar.php";
                      break;

                  case 'laporan':
                       include "pages/laporan.php";
                      break;

                  case 'tools':
                      include "pages/tools.php";
                      break;

                  case 'test_koneksi':
                      include "pages/test_koneksi.php";
                      break;

                  case 'tambah_data':
                      include "pages/tambah_data.php";
                      break;

                  case 'demo_api':
                      include "pages/demo_api.php";
                      break;

                  case 'profile':
                    include "pages/profil.php";
                      break;

                  case 'setting':
                      include "pages/setting.php";
                      break;

                  case 'logout':
                        include "logout.php";
                      break;

                  case 'help':
                      echo "<h3 style='padding:20px;'>Halaman Help</h3>";
                      break;

                  default:
                      include "pages/dashboard.php"; // fallback
              }

          // ✅ DEFAULT PAGE (tanpa ?page=...)
          } else {
              include "pages/dashboard.php"; 
          }
        ?>
      </section>
      <!-- ==== END ROUTER ==== -->

    </main>
    <!-- ================= END MAIN ================= -->

  </div>
</body>
</html>

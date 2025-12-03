<?php
include "koneksi.php";

// ✅ Misal id_user sudah ada di session
$id_user = $_SESSION['id_user'] ?? 1; // fallback ke ID 1 kalau belum login

$query = mysqli_query($conn, "SELECT * FROM user WHERE id_user='$id_user'");
$data = mysqli_fetch_assoc($query);
?>

<section class="profile-section">


  <div class="profile-container">
    <h3 class="profile-role"><?= $data['role']; ?></h3>
  </div>

  <div class="profile-info">
    <h3>Informasi Akun</h3>

    <div class="info-grid">
      <div class="info-item">
        <label>Nama Lengkap</label>
        <p id="infoName"><?= $data['nama']; ?></p>
      </div>

      <div class="info-item">
        <label>Email</label>
        <p id="infoEmail"><?= $data['email']; ?></p>
      </div>

      <div class="info-item">
        <label>Nomor Telepon</label>
        <p id="infoPhone"><?= $data['nomor']; ?></p>
      </div>

      <div class="info-item">
        <label>Username</label>
        <p id="infoUser"><?= $data['username']; ?></p>
      </div>

      <button class="btn-edit" onclick="openEdit()">Edit Profile</button>
    </div>
  </div>

</section>

<!-- POPUP EDIT -->
<div class="popup-overlay" id="popupEdit">
  <div class="popup-box">

    <h3>Edit Profile</h3>

    <form method="POST">
      <label>Nama Lengkap</label>
      <input type="text" name="nama" value="<?= $data['nama']; ?>">

      <label>Email</label>
      <input type="email" name="email" value="<?= $data['email']; ?>">

      <label>No Telepon</label>
      <input type="text" name="nomor" value="<?= $data['nomor']; ?>">

      <label>Username</label>
      <input type="text" name="username" value="<?= $data['username']; ?>">

      <div class="popup-actions">
        <button type="button" class="btn-cancel" onclick="closeEdit()">Batal</button>
        <button type="submit" name="simpan" class="btn-save">Simpan</button>
      </div>
    </form>

  </div>
</div>

<?php
// ✅ UPDATE DATA KETIKA DISIMPAN
if (isset($_POST['simpan'])) {

    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $nomor    = $_POST['nomor'];
    $username = $_POST['username'];

    mysqli_query($koneksi, "UPDATE user SET 
        nama='$nama',
        email='$email',
        nomor='$nomor',
        username='$username'
        WHERE id_user='$id_user'
    ");

    echo "<script>alert('Data berhasil diperbarui!'); location.href='index.php?page=profile';</script>";
}
?>
<!-- MODAL EDIT PROFILE -->
<div class="modal fade" id="modalEditProfile" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST">
        <div class="modal-body">

          <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama" value="<?= $data['nama']; ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= $data['email']; ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" name="nomor" value="<?= $data['nomor']; ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username" value="<?= $data['username']; ?>" required>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
        </div>

      </form>

    </div>
  </div>
</div>

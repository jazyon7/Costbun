<?php
require_once __DIR__ . '/../config/supabase_helper.php';

$id_user = $_SESSION['id_user'] ?? 1;
$userData = getUser($id_user);
if (!$userData) {
    $userData = ['nama' => '', 'email' => '', 'username' => ''];
}
?>
    <main>
      <header class="main-header">
        <div>
          <h2>Pengaturan</h2>
        </div>
      </header>

      <!-- ✅ Diubah dari div menjadi section -->
      <section class="content">

        <!-- ✅ Diubah menjadi section -->
        <section class="settings-container">

          <!-- ✅ Diubah menjadi section -->
          <section class="settings-card">
            <h3>Pengaturan Akun</h3>

            <div class="form-group">
              <label>Nama Admin</label>
              <input type="text" id="nama" value="<?= htmlspecialchars($userData['nama']); ?>" placeholder="Nama lengkap">
            </div>

            <div class="form-group">
              <label>Email</label>
              <input type="email" id="email" value="<?= htmlspecialchars($userData['email']); ?>" placeholder="Email admin">
            </div>

            <div class="form-group">
              <label>Password Baru</label>
              <input type="password" id="password" placeholder="Kosongkan jika tidak mengganti">
            </div>

            <button class="btn-save" onclick="saveSettings()">Simpan Perubahan</button>
            
            <script>
            function saveSettings() {
                const nama = document.getElementById('nama').value;
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                let updateData = { nama, email };
                if (password) updateData.password = password;
                
                fetch('api/user.php?action=update&id=<?= $id_user ?>', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams(updateData)
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message || 'Data berhasil diupdate!');
                    location.reload();
                })
                .catch(err => alert('Error: ' + err));
            }
            </script>
          </section>

          <!-- ✅ Diubah menjadi section -->
          <section class="settings-card">
            <h3>Pengaturan Sistem</h3>

            <div class="form-group">
              <label>Bahasa</label>
              <select>
                <option>Indonesia</option>
                <option>English</option>
              </select>
            </div>

            <div class="form-group">
              <label>Tema Tampilan</label>
              <select>
                <option>Light</option>
                <option>Dark</option>
                <option>Auto</option>
              </select>
            </div>

            <button class="btn-save">Simpan Pengaturan</button>
          </section>

          <!-- ✅ Diubah menjadi section -->
          <section class="settings-card">
            <h3>Pengaturan Notifikasi</h3>

            <div class="checkbox-group">
              <input type="checkbox" id="notif1" checked>
              <label for="notif1">Notifikasi Laporan Baru</label>
            </div>

            <div class="checkbox-group">
              <input type="checkbox" id="notif2">
              <label for="notif2">Notifikasi Laporan Selesai</label>
            </div>

            <div class="checkbox-group">
              <input type="checkbox" id="notif3" checked>
              <label for="notif3">Notifikasi Error Sistem</label>
            </div>

            <button class="btn-save">Simpan Notifikasi</button>
          </section>

        </section>
      </section>

    </main>

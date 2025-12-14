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
                const nama = document.getElementById('nama').value.trim();
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value.trim();
                
                // Validasi
                if (!nama || !email) {
                    alert('❌ Nama dan email wajib diisi!');
                    return;
                }
                
                if (password && password.length < 6) {
                    alert('❌ Password minimal 6 karakter!');
                    return;
                }
                
                let updateData = { nama, email };
                if (password) updateData.password = password;
                
                fetch('api/user.php?action=update&id=<?= $id_user ?>', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams(updateData)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Update session via AJAX
                        fetch('api/user.php?action=update_session', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({ nama, email })
                        })
                        .then(() => {
                            alert('✅ ' + (data.message || 'Data berhasil diperbarui!'));
                            location.reload();
                        });
                    } else {
                        alert('❌ ' + (data.message || 'Gagal memperbarui data'));
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('❌ Terjadi kesalahan: ' + err.message);
                });
            }
            </script>
          </section>

          <!-- ✅ Diubah menjadi section -->
          <section class="settings-card">
            <h3>Pengaturan Sistem</h3>

            <div class="form-group">
              <label>Bahasa</label>
              <select id="bahasa">
                <option value="id" selected>Indonesia</option>
                <option value="en">English</option>
              </select>
            </div>

            <div class="form-group">
              <label>Tema Tampilan</label>
              <select id="tema">
                <option value="light" selected>Light</option>
                <option value="dark">Dark</option>
                <option value="auto">Auto</option>
              </select>
            </div>

            <button class="btn-save" onclick="saveSystemSettings()">Simpan Pengaturan</button>
            
            <script>
            function saveSystemSettings() {
                try {
                    const bahasa = document.getElementById('bahasa').value;
                    const tema = document.getElementById('tema').value;
                    
                    console.log('Saving settings - Bahasa:', bahasa, 'Tema:', tema);
                    
                    // Save to localStorage
                    localStorage.setItem('app_bahasa', bahasa);
                    localStorage.setItem('app_tema', tema);
                    
                    console.log('Settings saved to localStorage');
                    
                    // Apply theme immediately
                    applyTheme(tema);
                    
                    console.log('Theme applied, body classes:', document.body.className);
                    
                    alert('✅ Pengaturan sistem berhasil disimpan!');
                } catch (error) {
                    console.error('Error saving settings:', error);
                    alert('❌ Gagal menyimpan pengaturan: ' + error.message);
                }
            }
            
            function applyTheme(tema) {
                console.log('[Setting] Applying theme:', tema);
                
                // Use global theme applier if available
                if (window.applyAppTheme) {
                    console.log('[Setting] Using global theme applier');
                    window.applyAppTheme(tema);
                } else {
                    console.log('[Setting] Using local theme applier');
                    
                    // Remove existing theme class
                    document.body.classList.remove('dark-theme');
                    
                    if (tema === 'dark') {
                        console.log('[Setting] Adding dark-theme class to body');
                        document.body.classList.add('dark-theme');
                    } else if (tema === 'auto') {
                        // Check system preference
                        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                        console.log('[Setting] Auto mode - System prefers dark:', prefersDark);
                        if (prefersDark) {
                            document.body.classList.add('dark-theme');
                        }
                    }
                }
                
                console.log('[Setting] Body classes after apply:', document.body.className);
            }
            
            // Load saved settings on page load
            window.addEventListener('DOMContentLoaded', function() {
                console.log('Loading saved settings...');
                
                const savedBahasa = localStorage.getItem('app_bahasa');
                const savedTema = localStorage.getItem('app_tema');
                
                console.log('Saved bahasa:', savedBahasa);
                console.log('Saved tema:', savedTema);
                
                if (savedBahasa && document.getElementById('bahasa')) {
                    document.getElementById('bahasa').value = savedBahasa;
                }
                
                if (savedTema && document.getElementById('tema')) {
                    document.getElementById('tema').value = savedTema;
                    // Also apply the saved theme
                    applyTheme(savedTema);
                } else {
                    // Default to light if no saved preference
                    if (document.getElementById('tema')) {
                        document.getElementById('tema').value = 'light';
                    }
                }
            });
            </script>
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

            <button class="btn-save" onclick="saveNotificationSettings()">Simpan Notifikasi</button>
            
            <script>
            function saveNotificationSettings() {
                const notif1 = document.getElementById('notif1').checked;
                const notif2 = document.getElementById('notif2').checked;
                const notif3 = document.getElementById('notif3').checked;
                
                // Save to localStorage
                localStorage.setItem('notif_laporan_baru', notif1);
                localStorage.setItem('notif_laporan_selesai', notif2);
                localStorage.setItem('notif_error_sistem', notif3);
                
                alert('✅ Pengaturan notifikasi berhasil disimpan!');
            }
            
            // Load saved notification settings
            window.addEventListener('DOMContentLoaded', function() {
                const notif1 = localStorage.getItem('notif_laporan_baru');
                const notif2 = localStorage.getItem('notif_laporan_selesai');
                const notif3 = localStorage.getItem('notif_error_sistem');
                
                if (notif1 !== null) document.getElementById('notif1').checked = (notif1 === 'true');
                if (notif2 !== null) document.getElementById('notif2').checked = (notif2 === 'true');
                if (notif3 !== null) document.getElementById('notif3').checked = (notif3 === 'true');
            });
            </script>
          </section>

        </section>
      </section>

    </main>

<?php
// Cek role user yang sedang login
$currentUserRole = $_SESSION['role'] ?? 'penghuni kos';
$currentUserId = $_SESSION['id_user'] ?? 0;
$isAdmin = ($currentUserRole === 'admin');
?>

<section class="notifikasi-section">

  <header class="main-header">
      <h2>Notifikasi</h2>
      <p style="color: #666; font-size: 14px;">
          <?= $isAdmin ? 'Kelola dan kirim notifikasi ke penghuni kos' : 'Lihat notifikasi yang dikirim ke Anda' ?>
      </p>
  </header>

  <?php if (isset($_GET['msg'])): ?>
  <div class="alert alert-success">
      <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_GET['msg']) ?>
  </div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
  <div class="alert alert-error">
      <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_GET['error']) ?>
  </div>
  <?php endif; ?>

  <div class="content">
      
      <?php if ($isAdmin): ?>
      <div class="notif-actions">
          <button class="btn-add-notif" onclick="openCreateNotifModal()">
              <i class="fas fa-plus"></i> Buat Notifikasi
          </button>
          <div class="filter-tabs">
              <button class="tab-btn active" onclick="filterNotif('semua')">Semua</button>
              <button class="tab-btn" onclick="filterNotif('pengumuman')">📢 Pengumuman</button>
              <button class="tab-btn" onclick="filterNotif('acara')">🎉 Acara</button>
              <button class="tab-btn" onclick="filterNotif('tagihan')">💰 Tagihan</button>
              <button class="tab-btn" onclick="filterNotif('peringatan')">⚠️ Peringatan</button>
              <button class="tab-btn" onclick="filterNotif('maintenance')">🔧 Maintenance</button>
              <button class="tab-btn" onclick="filterNotif('info')">ℹ️ Info</button>
          </div>
      </div>
      <?php endif; ?>

      <section id="notif-list" class="notif-list">
          <!-- Notifikasi akan digenerate oleh JS -->
          <div class="loading">
              <i class="fas fa-spinner fa-spin"></i> Loading notifikasi...
          </div>
      </section>

  </div>

</section>

<style>
    .alert {
        padding: 15px 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        font-weight: 500;
        animation: slideDown 0.3s ease;
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .notif-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .btn-add-notif {
        background: #3681ff;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-add-notif:hover {
        background: #2d6ad4;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(54, 129, 255, 0.3);
    }
    
    .filter-tabs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .tab-btn {
        padding: 8px 16px;
        border: 2px solid #e0e0e0;
        background: white;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .tab-btn:hover {
        border-color: #3681ff;
        color: #3681ff;
    }
    
    .tab-btn.active {
        background: #3681ff;
        color: white;
        border-color: #3681ff;
    }
    
    .notif-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .notif-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 4px solid #3681ff;
        transition: all 0.3s;
        position: relative;
    }
    
    .notif-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .notif-card.unread {
        background: #f0f7ff;
        border-left-color: #3681ff;
    }
    
    .notif-card.read {
        background: white;
        border-left-color: #e0e0e0;
    }
    
    .notif-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 12px;
    }
    
    .notif-type {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 24px;
    }
    
    .notif-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 8px 0;
    }
    
    .notif-message {
        color: #666;
        line-height: 1.6;
        margin-bottom: 12px;
    }
    
    .notif-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        color: #999;
        padding-top: 12px;
        border-top: 1px solid #eee;
    }
    
    .notif-date {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .notif-actions-btn {
        display: flex;
        gap: 8px;
    }
    
    .btn-read,
    .btn-delete-notif {
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-read {
        background: #4CAF50;
        color: white;
    }
    
    .btn-read:hover {
        background: #45a049;
    }
    
    .btn-delete-notif {
        background: #f44336;
        color: white;
    }
    
    .btn-delete-notif:hover {
        background: #da190b;
    }
    
    .badge-unread {
        background: #3681ff;
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
    }
    
    .loading {
        text-align: center;
        padding: 40px;
        color: #999;
        font-size: 16px;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }
    
    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }
    
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
        animation: fadeIn 0.3s;
    }
    
    .modal-content {
        background-color: #fefefe;
        margin: 3% auto;
        padding: 30px;
        border-radius: 10px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.3);
        animation: slideDown 0.3s;
    }
    
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        line-height: 20px;
        cursor: pointer;
    }
    
    .close:hover {
        color: #000;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
    }
    
    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
        font-size: 14px;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #3681ff;
        box-shadow: 0 0 0 3px rgba(54, 129, 255, 0.1);
    }
    
    .form-group textarea {
        resize: vertical;
    }
    
    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .btn-cancel,
    .btn-submit {
        padding: 12px 24px;
        border: none;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-cancel {
        background: #f5f5f5;
        color: #666;
    }
    
    .btn-cancel:hover {
        background: #e0e0e0;
    }
    
    .btn-submit {
        background: #3681ff;
        color: white;
    }
    
    .btn-submit:hover {
        background: #2d6ad4;
    }
    
    .btn-submit:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Modal Buat Notifikasi (Admin Only) -->
<?php if ($isAdmin): ?>
<div id="createNotifModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeCreateNotifModal()">&times;</span>
        <h2 style="margin-top: 0; color: #333;">
            <i class="fas fa-bell"></i> Buat Notifikasi Baru
        </h2>
        
        <form id="formCreateNotif" onsubmit="submitCreateNotif(event)">
            <div class="form-group">
                <label>Tipe Notifikasi *</label>
                <select name="tipe" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="pengumuman">📢 Pengumuman</option>
                    <option value="acara">🎉 Acara/Event</option>
                    <option value="tagihan">💰 Tagihan</option>
                    <option value="peringatan">⚠️ Peringatan</option>
                    <option value="maintenance">🔧 Maintenance</option>
                    <option value="info">ℹ️ Informasi</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Judul *</label>
                <input type="text" name="judul" required placeholder="Contoh: Acara Bersih-Bersih Kos">
            </div>
            
            <div class="form-group">
                <label>Pesan *</label>
                <textarea name="pesan" rows="4" required placeholder="Tulis pesan notifikasi..."></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Kirim</label>
                    <input type="date" name="tanggal_kirim" value="<?= date('Y-m-d') ?>">
                </div>
                
                <div class="form-group">
                    <label>Kirim ke</label>
                    <select name="send_to" id="send_to" onchange="toggleUserSelect()">
                        <option value="all">Semua Penghuni</option>
                        <option value="specific">Penghuni Tertentu</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group" id="userSelectGroup" style="display: none;">
                <label>Pilih Penghuni *</label>
                <select name="id_user[]" id="id_user_select" multiple size="5">
                    <?php
                    require_once __DIR__ . '/../config/supabase_helper.php';
                    $userList = getUser();
                    if (is_array($userList)) {
                        foreach ($userList as $user) {
                            if (strtolower($user['role']) !== 'admin') {
                                echo '<option value="' . $user['id_user'] . '">' . htmlspecialchars($user['nama']) . ' (' . htmlspecialchars($user['email']) . ')</option>';
                            }
                        }
                    }
                    ?>
                </select>
                <small style="color: #666;">Tahan Ctrl/Cmd untuk pilih multiple</small>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeCreateNotifModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Kirim Notifikasi
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
    const isAdmin = <?php echo json_encode($isAdmin); ?>;
    const currentUserId = <?php echo json_encode($currentUserId); ?>;
    let allNotifications = [];
    let currentFilter = 'semua';
    
    // Load notifications on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadNotifications();
        
        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    });
    
    // Load notifications from API
    function loadNotifications() {
        const notifList = document.getElementById('notif-list');
        if (!notifList) {
            console.error('Element notif-list tidak ditemukan!');
            return;
        }
        
        notifList.innerHTML = '<div class="loading">⏳ Memuat notifikasi...</div>';
        
        let apiUrl = 'api/notifikasi_data.php';
        if (!isAdmin) {
            apiUrl += '?user_id=' + currentUserId;
        }
        if (currentFilter !== 'semua') {
            apiUrl += (apiUrl.includes('?') ? '&' : '?') + 'type=' + currentFilter;
        }
        
        console.log('Fetching notifikasi dari:', apiUrl);
        console.log('isAdmin:', isAdmin, 'currentUserId:', currentUserId);
        
        fetch(apiUrl)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('HTTP error ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Data diterima:', data);
                console.log('Jumlah notifikasi:', data.length);
                allNotifications = data;
                renderNotifications(data);
            })
            .catch(error => {
                console.error('Error loading notifikasi:', error);
                notifList.innerHTML = '<div class="empty-state"><p>⚠️ Gagal memuat notifikasi: ' + error.message + '</p></div>';
            });
    }
    
    // Render notifications to DOM
    function renderNotifications(notifications) {
        const notifList = document.getElementById('notif-list');
        
        if (notifications.length === 0) {
            notifList.innerHTML = '<div class="empty-state"><i>📭</i><p>Belum ada notifikasi</p></div>';
            return;
        }
        
        notifList.innerHTML = notifications.map(notif => {
            const icon = getNotifIcon(notif.tipe);
            const statusClass = notif.status === 'unread' ? 'unread' : 'read';
            const date = new Date(notif.tanggal_kirim).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
            
            let actionsHtml = '';
            if (notif.status === 'unread') {
                actionsHtml += `<button class="btn-read" onclick="markAsRead(${notif.id_notif})">✓ Tandai Dibaca</button>`;
            }
            if (isAdmin) {
                actionsHtml += `<button class="btn-delete-notif" onclick="deleteNotif(${notif.id_notif})">🗑️ Hapus</button>`;
            }
            
            return `
                <div class="notif-card ${statusClass}">
                    <div class="notif-header">
                        <div class="notif-type">${icon}</div>
                        ${notif.status === 'unread' ? '<span class="badge-unread">BARU</span>' : ''}
                    </div>
                    <div class="notif-title">${notif.judul}</div>
                    <div class="notif-message">${notif.pesan}</div>
                    <div class="notif-footer">
                        <div class="notif-date">📅 ${date}</div>
                        <div class="notif-actions-btn">
                            ${actionsHtml}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }
    
    // Get emoji icon based on notification type
    function getNotifIcon(type) {
        const icons = {
            'pengumuman': '📢',
            'acara': '🎉',
            'tagihan': '💰',
            'peringatan': '⚠️',
            'maintenance': '🔧',
            'info': 'ℹ️'
        };
        return icons[type] || 'ℹ️';
    }
    
    // Filter notifications
    function filterNotif(type) {
        currentFilter = type;
        
        // Update active tab
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
        
        // Reload with filter
        loadNotifications();
    }
    
    // Modal functions
    function openCreateNotifModal() {
        document.getElementById('createNotifModal').style.display = 'block';
    }
    
    function closeCreateNotifModal() {
        document.getElementById('createNotifModal').style.display = 'none';
        document.getElementById('createNotifForm').reset();
        document.getElementById('user-select-group').style.display = 'none';
    }
    
    function toggleUserSelect() {
        const sendTo = document.getElementById('send_to').value;
        const userSelectGroup = document.getElementById('user-select-group');
        
        if (sendTo === 'specific') {
            userSelectGroup.style.display = 'block';
        } else {
            userSelectGroup.style.display = 'none';
        }
    }
    
    // Submit create notification form
    function submitCreateNotif(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('.btn-submit');
        
        submitBtn.disabled = true;
        submitBtn.textContent = '⏳ Mengirim...';
        
        fetch('api/notifikasi.php?action=create', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '?page=notifikasi&msg=' + encodeURIComponent(data.message);
            } else {
                alert('Error: ' + data.message);
                submitBtn.disabled = false;
                submitBtn.textContent = '📤 Kirim Notifikasi';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim notifikasi');
            submitBtn.disabled = false;
            submitBtn.textContent = '📤 Kirim Notifikasi';
        });
    }
    
    // Mark notification as read
    function markAsRead(id) {
        fetch('api/notifikasi.php?action=update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id_notif: id,
                status: 'read'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    // Delete notification (admin only)
    function deleteNotif(id) {
        if (!confirm('Yakin ingin menghapus notifikasi ini?')) {
            return;
        }
        
        fetch('api/notifikasi.php?action=delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id_notif: id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus notifikasi');
        });
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('createNotifModal');
        if (event.target == modal) {
            closeCreateNotifModal();
        }
    }
</script>

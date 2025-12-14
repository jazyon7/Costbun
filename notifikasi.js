// DATA NOTIFIKASI - Load from Supabase
let notifikasi = [];

// NOTIFIKASI
async function loadNotif() {
    const container = document.getElementById("notifList");
    container.innerHTML = "<p>Loading...</p>";

    try {
        const response = await fetch('api/notifikasi_data.php');
        notifikasi = await response.json();
        
        container.innerHTML = "";

        if (notifikasi.length === 0) {
            container.innerHTML = "<p style='text-align: center; padding: 20px;'>Tidak ada notifikasi</p>";
            return;
        }

        notifikasi.forEach((n, i) => {
            // Icon berdasarkan tipe
            let icon = "fa-bell";
            if (n.tipe.toLowerCase().includes('pembayaran')) icon = "fa-money-bill";
            if (n.tipe.toLowerCase().includes('laporan')) icon = "fa-triangle-exclamation";
            if (n.tipe.toLowerCase().includes('tagihan')) icon = "fa-file-invoice";
            
            // Format waktu
            const waktu = formatWaktu(n.tanggal);
            
            container.innerHTML += `
                <div class="notif-card" data-status="${n.status}">
                    <i class="fa-solid ${icon} notif-icon"></i>

                    <div class="notif-content">
                        <h4>${n.judul}</h4>
                        <p>${n.pesan}</p>
                        <div class="notif-time">${waktu}</div>
                    </div>

                    <button class="notif-delete" onclick="deleteNotif(${n.id})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            `;
        });
    } catch (error) {
        container.innerHTML = "<p>Error loading notifications</p>";
        console.error('Error:', error);
    }
}

// Format waktu relatif
function formatWaktu(tanggal) {
    const now = new Date();
    const date = new Date(tanggal);
    const diff = Math.floor((now - date) / 1000); // detik
    
    if (diff < 60) return 'Baru saja';
    if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
    if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
    if (diff < 604800) return Math.floor(diff / 86400) + ' hari lalu';
    
    return date.toLocaleDateString('id-ID');
}

// HAPUS NOTIFIKASI
function deleteNotif(id) {
    if (confirm('Yakin ingin menghapus notifikasi ini?')) {
        window.location.href = `api/notifikasi.php?action=delete&id=${id}`;
    }
}
    loadNotif();
}


// LOAD SAAT HALAMAN DIBUKA
window.onload = loadNotif;

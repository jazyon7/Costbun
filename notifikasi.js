// DATA NOTIFIKASI
const notifikasi = [
    {
        icon: "fa-money-bill",
        judul: "Pembayaran Sewa Berhasil",
        isi: "Asep telah membayar sewa bulan ini.",
        waktu: "5 menit lalu"
    },
    {
        icon: "fa-money-bill",
        judul: "Pembayaran Sewa Berhasil",
        isi: "Andika Sukron telah membayar sewa bulan ini.",
        waktu: "30 menit lalu"
    },
    {
        icon: "fa-triangle-exclamation",
        judul: "Laporan",
        isi: "Kamar A- 02 mengalami kerusakan engsel pintu.",
        waktu: "1 jam lalu"
    },
];


// NOTIFIKASI
function loadNotif() {
    const container = document.getElementById("notifList");
    container.innerHTML = "";

    notifikasi.forEach((n, i) => {
        container.innerHTML += `
            <div class="notif-card">
                <i class="fa-solid ${n.icon} notif-icon"></i>

                <div class="notif-content">
                    <h4>${n.judul}</h4>
                    <p>${n.isi}</p>
                    <div class="notif-time">${n.waktu}</div>
                </div>

                <button class="notif-delete" onclick="deleteNotif(${i})">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;
    });
}

// HAPUS NOTIFIKASI
function deleteNotif(index) {
    notifikasi.splice(index, 1);
    loadNotif();
}


// LOAD SAAT HALAMAN DIBUKA
window.onload = loadNotif;

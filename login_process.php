<?php
session_start();
include "koneksi.php";

// Ambil input dari form
$username = $_POST['username'];
$password = $_POST['password'];

// Query menggunakan prepared statement (lebih aman)
$stmt = $conn->prepare("SELECT * FROM user WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if($data){

    // ✅ Cek jika password sudah HASH (password_hash)
    if(password_verify($password, $data['password'])){
        
        // Login berhasil
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        header("Location: index.php?page=profile");
        exit;
    }

    // ✅ Jika password TIDAK hash (masih plain text)
    if($password === $data['password']){ 
        
        // Login berhasil
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        header("Location: index.php?page=profile");
        exit;
    }

    // ✅ Kalau password salah
    header("Location: login.php?error=Password salah");
    exit;

} else {
    // ✅ Username tidak ada
    header("Location: login.php?error=Username tidak ditemukan");
    exit;
}
?>

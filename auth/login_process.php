<?php
session_start();
require_once '../config/supabase_request.php';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    header("Location: ../login.php?error=Input kosong");
    exit;
}

$response = supabase_request(
    'GET',
    "/rest/v1/user?username=eq.$username&limit=1"
);

if (empty($response)) {
    header("Location: ../login.php?error=Username tidak ditemukan");
    exit;
}

$user = $response[0];

// Cek password - support plain text (old) dan bcrypt (new)
$passwordValid = false;
if (password_verify($password, $user['password'])) {
    // Password di-hash dengan bcrypt
    $passwordValid = true;
} elseif (trim($password) === trim($user['password'])) {
    // Password masih plain text (untuk backward compatibility)
    $passwordValid = true;
}

if (!$passwordValid) {
    header("Location: ../login.php?error=Password salah");
    exit;
}

// LOGIN OK
$_SESSION['id_user'] = (int)$user['id_user'];
$_SESSION['nama']    = $user['nama'];
$_SESSION['role']    = $user['role'];

header("Location: ../index.php");
exit;

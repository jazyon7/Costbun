<?php
require_once __DIR__ . '/config/supabase_helper.php';

echo "<h2>Debug Tambah Data</h2>";

// Get data from database
$kamarList = getKamar();
$userList = getUser();

echo "<h3>Kamar List:</h3>";
echo "<pre>";
print_r($kamarList);
echo "</pre>";

echo "<h3>User List:</h3>";
echo "<pre>";
print_r($userList);
echo "</pre>";

echo "<h3>Is Array Check:</h3>";
echo "Kamar is array: " . (is_array($kamarList) ? "YES" : "NO") . "<br>";
echo "User is array: " . (is_array($userList) ? "YES" : "NO") . "<br>";

if (is_array($kamarList)) {
    echo "<h3>Kamar Count: " . count($kamarList) . "</h3>";
    echo "<h4>Dropdown Preview:</h4>";
    echo '<select>';
    echo '<option value="">-- Pilih Kamar --</option>';
    foreach ($kamarList as $kamar) {
        echo '<option value="' . $kamar['id_kamar'] . '">';
        echo htmlspecialchars($kamar['nama']) . ' - Rp ' . number_format($kamar['harga'], 0, ',', '.') . ' (' . ucfirst($kamar['status']) . ')';
        echo '</option>';
    }
    echo '</select>';
}

if (is_array($userList)) {
    echo "<h3>User Count: " . count($userList) . "</h3>";
    echo "<h4>Dropdown Preview:</h4>";
    echo '<select>';
    echo '<option value="">-- Pilih User --</option>';
    foreach ($userList as $user) {
        echo '<option value="' . $user['id_user'] . '">';
        echo htmlspecialchars($user['nama']) . ' - ' . htmlspecialchars($user['email']);
        echo '</option>';
    }
    echo '</select>';
}
?>

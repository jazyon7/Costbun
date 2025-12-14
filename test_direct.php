<!DOCTYPE html>
<html>
<head>
    <title>Test Direct Access</title>
</head>
<body>
    <h1>Test Direct Access</h1>
    
    <h2>1. Test Include Dashboard</h2>
    <?php
    session_start();
    $_SESSION['id_user'] = 1; // Dummy session
    
    try {
        include "pages/dashboard.php";
        echo "<p style='color:green'>✓ Dashboard berhasil diload</p>";
    } catch(Exception $e) {
        echo "<p style='color:red'>✗ Error: " . $e->getMessage() . "</p>";
    }
    ?>
    
    <hr>
    
    <h2>2. Test API Kamar</h2>
    <?php
    $api_url = "http://localhost/Costbun/api/kamar.php?action=get";
    $response = @file_get_contents($api_url);
    if($response) {
        echo "<p style='color:green'>✓ API Kamar berhasil diakses</p>";
        echo "<pre>" . htmlspecialchars(substr($response, 0, 500)) . "...</pre>";
    } else {
        echo "<p style='color:red'>✗ API Kamar gagal diakses</p>";
    }
    ?>
    
    <hr>
    
    <h2>3. Test Config Files</h2>
    <?php
    if(file_exists("config/supabase.php")) {
        echo "<p style='color:green'>✓ config/supabase.php ada</p>";
    } else {
        echo "<p style='color:red'>✗ config/supabase.php TIDAK ada</p>";
    }
    
    if(file_exists("config/supabase_helper.php")) {
        echo "<p style='color:green'>✓ config/supabase_helper.php ada</p>";
    } else {
        echo "<p style='color:red'>✗ config/supabase_helper.php TIDAK ada</p>";
    }
    
    if(file_exists("config/supabase_request.php")) {
        echo "<p style='color:green'>✓ config/supabase_request.php ada</p>";
    } else {
        echo "<p style='color:red'>✗ config/supabase_request.php TIDAK ada</p>";
    }
    ?>
    
    <hr>
    
    <h2>4. Test Helper Function</h2>
    <?php
    try {
        require_once "config/supabase_helper.php";
        $kamar = getKamar();
        if($kamar) {
            echo "<p style='color:green'>✓ getKamar() berhasil</p>";
            echo "<p>Total kamar: " . count($kamar) . "</p>";
        } else {
            echo "<p style='color:orange'>⚠ getKamar() return empty</p>";
        }
    } catch(Exception $e) {
        echo "<p style='color:red'>✗ Error: " . $e->getMessage() . "</p>";
    }
    ?>
    
    <hr>
    <a href="index.php">Kembali ke Dashboard</a>
</body>
</html>

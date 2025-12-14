<?php
require_once __DIR__ . '/config/supabase_helper.php';

echo "<h1>🔍 Debug getUser()</h1>";
echo "<style>body{font-family:Arial;padding:20px;} pre{background:#f5f5f5;padding:10px;border-radius:5px;}</style>";

echo "<h2>1. Test getUser():</h2>";
$result = getUser();

echo "<p>Type: " . gettype($result) . "</p>";
echo "<p>Is Array: " . (is_array($result) ? 'Yes' : 'No') . "</p>";

if (is_array($result)) {
    echo "<p>Count: " . count($result) . "</p>";
    echo "<h3>Data:</h3>";
    echo "<pre>" . print_r($result, true) . "</pre>";
} else {
    echo "<p style='color:red;'>Result is not array!</p>";
    echo "<pre>" . print_r($result, true) . "</pre>";
}

echo "<hr>";
echo "<h2>2. Test Direct API Call:</h2>";
require_once __DIR__ . '/config/supabase_request.php';
$apiResult = supabase_request('GET', '/rest/v1/user?order=id.asc&limit=3');
echo "<pre>" . print_r($apiResult, true) . "</pre>";

echo "<hr>";
echo "<p><a href='index.php?page=data_kos'>Kembali ke Data Kos</a></p>";
?>

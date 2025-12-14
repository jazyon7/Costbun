<?php
require_once __DIR__ . '/config/supabase_helper.php';

echo "<h1>Test Supabase Keuangan API</h1>";
echo "<pre>";

echo "\n=== GET ALL KEUANGAN ===\n";
$keuanganList = getKeuangan();
echo "Total records: " . count($keuanganList) . "\n";
print_r($keuanganList);

echo "\n\n=== TEST CREATE KEUANGAN ===\n";
$testData = [
    'tanggal_tranksaksi' => date('Y-m-d'),
    'jenis' => 'pemasukan',
    'keterangan' => 'Pembayaran kos bulan Desember',
    'jumlah' => 1500000,
    'sumber' => 'Kamar 101 - Test User'
];

echo "Data to insert:\n";
print_r($testData);

$result = createKeuangan($testData);
echo "\nResult:\n";
print_r($result);

echo "\n\n=== VERIFY DATA ===\n";
$keuanganList = getKeuangan();
echo "Total records after insert: " . count($keuanganList) . "\n";
echo "Latest 3 records:\n";
print_r(array_slice($keuanganList, 0, 3));

echo "</pre>";

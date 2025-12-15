<?php
require_once 'config/supabase_helper.php';

echo "<h2>📤 Upload Sample Gambar ke Supabase Storage</h2>";

// Create a simple test image
function createSampleImage($text, $filename) {
    $width = 800;
    $height = 600;
    $image = imagecreatetruecolor($width, $height);
    
    // Colors
    $bgColor = imagecolorallocate($image, 240, 240, 240);
    $textColor = imagecolorallocate($image, 51, 51, 51);
    $borderColor = imagecolorallocate($image, 33, 150, 243);
    
    // Fill background
    imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);
    
    // Draw border
    imagerectangle($image, 10, 10, $width-10, $height-10, $borderColor);
    imagerectangle($image, 11, 11, $width-11, $height-11, $borderColor);
    
    // Add text
    $font = 5; // Built-in font
    $textWidth = imagefontwidth($font) * strlen($text);
    $textHeight = imagefontheight($font);
    $x = ($width - $textWidth) / 2;
    $y = ($height - $textHeight) / 2;
    
    imagestring($image, $font, $x, $y - 50, "BUKTI PEMBAYARAN", $textColor);
    imagestring($image, $font, $x - 50, $y, $text, $textColor);
    imagestring($image, $font, $x - 100, $y + 50, "Transfer Bank BCA", $textColor);
    imagestring($image, $font, $x - 80, $y + 100, date('d M Y H:i:s'), $textColor);
    
    // Save to temp file
    $tempFile = sys_get_temp_dir() . '/' . $filename;
    imagejpeg($image, $tempFile, 90);
    imagedestroy($image);
    
    return $tempFile;
}

echo "<div style='padding: 20px; background: #f5f5f5; border-radius: 8px; margin: 20px 0;'>";

// Create and upload 5 sample images
$uploadedUrls = [];
for ($i = 1; $i <= 5; $i++) {
    $filename = time() . "_sample_$i.jpg";
    $text = "Rp " . number_format(rand(500, 2000) * 1000, 0, ',', '.');
    
    echo "<h3>Upload Sample #$i</h3>";
    
    // Create sample image
    $tempFile = createSampleImage($text, $filename);
    
    if (!file_exists($tempFile)) {
        echo "<p style='color: red;'>❌ Gagal membuat gambar sample</p>";
        continue;
    }
    
    // Prepare file array for upload
    $fileData = [
        'name' => $filename,
        'type' => 'image/jpeg',
        'tmp_name' => $tempFile,
        'error' => 0,
        'size' => filesize($tempFile)
    ];
    
    // Upload to Supabase Storage
    $result = uploadToSupabaseStorage($fileData, 'uploads', 'pembayaran');
    
    if ($result['success']) {
        $uploadedUrls[] = $result['url'];
        echo "<p style='color: green;'>✅ Berhasil upload: <code>{$result['url']}</code></p>";
        echo "<img src='{$result['url']}' style='max-width: 300px; border: 2px solid #4CAF50; border-radius: 8px; margin: 10px 0;'>";
    } else {
        echo "<p style='color: red;'>❌ Gagal upload: " . json_encode($result['error']) . "</p>";
    }
    
    // Clean up temp file
    if (file_exists($tempFile)) {
        unlink($tempFile);
    }
    
    echo "<hr style='margin: 20px 0;'>";
}

echo "</div>";

// Update existing tagihan with uploaded images
if (!empty($uploadedUrls)) {
    echo "<h3>🔄 Update Data Tagihan dengan Gambar</h3>";
    echo "<div style='padding: 20px; background: #e3f2fd; border-radius: 8px; margin: 20px 0;'>";
    
    // Get tagihan without bukti
    $tagihanList = getTagihan();
    $tagihanNoBukti = array_filter($tagihanList, function($t) {
        return empty($t['bukti_pembayaran']) && $t['status_pembayaran'] !== 'lunas';
    });
    
    $updated = 0;
    foreach ($tagihanNoBukti as $index => $tagihan) {
        if ($index >= count($uploadedUrls)) break;
        
        $url = $uploadedUrls[$index];
        $result = updateTagihan($tagihan['id_tagihan'], [
            'bukti_pembayaran' => $url,
            'status_pembayaran' => 'pending' // Change to pending since they now have bukti
        ]);
        
        if (!isset($result['error'])) {
            $updated++;
            $namaUser = isset($tagihan['user']['nama']) ? $tagihan['user']['nama'] : 'Unknown';
            echo "<p style='color: green;'>✅ Updated tagihan #{$tagihan['id_tagihan']} - $namaUser</p>";
        }
    }
    
    echo "<p style='margin-top: 20px;'><strong>Total updated:</strong> $updated tagihan</p>";
    echo "</div>";
}

// Show summary
echo "<div style='padding: 20px; background: #d4edda; border-radius: 8px; margin: 20px 0;'>";
echo "<h3 style='color: #155724;'>📊 Summary:</h3>";
echo "<p><strong>Gambar di-upload:</strong> " . count($uploadedUrls) . "</p>";
if (!empty($uploadedUrls)) {
    echo "<p><strong>Sample URLs:</strong></p>";
    echo "<ul style='font-size: 12px; word-break: break-all;'>";
    foreach ($uploadedUrls as $url) {
        echo "<li><a href='$url' target='_blank'>$url</a></li>";
    }
    echo "</ul>";
}
echo "</div>";

echo "<hr>";
echo "<p style='margin-top: 30px;'>";
echo "<a href='index.php?page=bukti_pembayaran' style='padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>← Lihat Bukti Pembayaran</a>";
echo "<a href='test_bukti_pembayaran.php' style='padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Test Gambar</a>";
echo "</p>";
?>

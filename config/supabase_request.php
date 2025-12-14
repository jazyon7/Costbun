<?php
require_once 'supabase.php';

function supabase_request($method, $endpoint, $body = null)
{
    $curl = curl_init();
    
    // Build headers
    $headers = [
        "apikey: " . SUPABASE_API_KEY,
        "Authorization: Bearer " . SUPABASE_API_KEY,
        "Content-Type: application/json"
    ];
    
    // Add Prefer header for PATCH/POST to return updated data
    if ($method === 'PATCH' || $method === 'POST') {
        $headers[] = "Prefer: return=representation";
    }

    curl_setopt_array($curl, [
        CURLOPT_URL => SUPABASE_URL . $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $body ? json_encode($body) : null
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);

    curl_close($curl);

    // Log untuk debugging
    error_log("Supabase Request - Method: $method, Endpoint: $endpoint");
    error_log("Supabase Request - Body: " . ($body ? json_encode($body) : 'null'));
    error_log("Supabase Request - HTTP Code: $httpCode");
    error_log("Supabase Request - Response: " . substr($response, 0, 500));

    if ($error) {
        error_log("Supabase Request - cURL Error: $error");
        return [
            'error' => true,
            'message' => $error
        ];
    }
    
    // Check HTTP status
    if ($httpCode >= 400) {
        error_log("Supabase Request - HTTP Error $httpCode: $response");
        $decoded = json_decode($response, true);
        return [
            'error' => true,
            'message' => $decoded['message'] ?? "HTTP Error $httpCode",
            'details' => $decoded
        ];
    }

    $decoded = json_decode($response, true);
    
    // PATCH/DELETE might return empty array on success
    if (empty($decoded) && in_array($method, ['PATCH', 'DELETE']) && $httpCode >= 200 && $httpCode < 300) {
        // Success but no data returned - this is OK for PATCH/DELETE
        return ['success' => true];
    }
    
    return $decoded;
}

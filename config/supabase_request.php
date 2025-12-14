<?php
require_once 'supabase.php';

function supabase_request($method, $endpoint, $body = null)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => SUPABASE_URL . $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => [
            "apikey: " . SUPABASE_API_KEY,
            "Authorization: Bearer " . SUPABASE_API_KEY,
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => $body ? json_encode($body) : null
    ]);

    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);

    if ($error) {
        return [
            'error' => true,
            'message' => $error
        ];
    }

    return json_decode($response, true);
}

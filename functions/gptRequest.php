<?php
function gptRequest($message) {
    $url = 'https://api.openai.com/v1/engines/davinci-codex/completions';
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer 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'
    );

    $data = array(
        'prompt' => $message,
        'max_tokens' => 50 // Jumlah token maksimum yang dihasilkan oleh model
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true)['choices'][0]['text'];
}
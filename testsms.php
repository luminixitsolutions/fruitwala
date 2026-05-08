<?php
$apiKey = "Znj1aXR3YWxhYnJlYWtmYXN0QGdtYWlsLmNvbQ";
$baseUrl = "https://crm.campaignplus.in";
$mobile = "919595454907";
$message = "Hello Rajat, your order has been successfully delivered. Thank you for choosing Fruitwala Breakfast!";

// Possible endpoints (CampaignPlus variations)
$possibleEndpoints = [
    "/api/v1/message/send",
    "/api/v1/messages/send",
    "/api/v1/whatsapp/send",
    "/api/v1/sendMessage",
    "/api/sendMessage",
    "/sendMessage",
];

foreach ($possibleEndpoints as $endpoint) {
    $url = $baseUrl . $endpoint;
    echo "<b>Trying:</b> $url <br>";

    $payload = [
        "apiKey" => $apiKey,
        "mobileNumbers" => $mobile,
        "message" => $message,
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "HTTP Code: $httpCode <br>Response: $response<br><hr>";

    // If response looks valid (not "Cannot POST" or "invalid path")
    if ($httpCode == 200 && stripos($response, 'error') === false && stripos($response, 'cannot post') === false) {
        echo "✅ Found working endpoint: $url <br>Response: $response";
        exit;
    }
}

echo "❌ None worked. Check your CampaignPlus developer API documentation for the exact path.";
?>

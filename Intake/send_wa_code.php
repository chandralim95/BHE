<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (empty($email)) {
        echo 'error';
        exit;
    }

    // Dummy
    $dummyNumbers = [
        'user@example.com' => '628977168889',
        'admin@example.com' => '628987654321'
    ];

    if (!array_key_exists($email, $dummyNumbers)) {
        echo 'error';
        exit;
    }

    // Generate code dan store
    $secretCode = rand(100000, 999999);
    $_SESSION['wa_secret_code'] = $secretCode;

    $phoneNumber = $dummyNumbers[$email];
    $message = $secretCode;

    // Kirim ke WhatsApp menggunakan API
    $url = "https://api.watzap.id/v1/send_message";
    $data = [
        "api_key" => "HKLACYKAMEKPZDCK",
        "number_key" => "O3j5viLxzHydtOEy",
        "phone_no" => $phoneNumber,
        "message" => $message,
        "wait_until_send" => "1"
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        file_put_contents('dummy_wa_log.txt', "Error sending to WhatsApp: " . curl_error($ch) . PHP_EOL, FILE_APPEND);
        echo 'error';
    } else {
        // Simpan log lokal
        file_put_contents('dummy_wa_log.txt', "To: $phoneNumber - Message: $message - API Response: $response" . PHP_EOL, FILE_APPEND);
        echo 'success';
    }

    curl_close($ch);
}

?>

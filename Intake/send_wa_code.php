<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (empty($email)) {
        echo 'error';
        exit;
    }

    //Dummy
    $dummyNumbers = [
        'user@example.com' => '628123456789',
        'admin@example.com' => '628987654321'
    ];

    if (!array_key_exists($email, $dummyNumbers)) {
        echo 'error';
        exit;
    }

    //Generate code dan store
    $secretCode = rand(100000, 999999);
    $_SESSION['wa_secret_code'] = $secretCode;

    $phoneNumber = $dummyNumbers[$email];
    $message = "Secret code: $secretCode";

    //Simulasi
    file_put_contents('dummy_wa_log.txt', "To: $phoneNumber - Message: $message" . PHP_EOL, FILE_APPEND);

    echo 'success';
}
?>

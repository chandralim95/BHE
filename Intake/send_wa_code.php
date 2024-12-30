<?php

session_start();

$servername = "LAPTOP-DLPOU7KE";
$username = "LAPTOP-DLPOU7KE\ASUS";
$password = "";
$dbname = "BHE";

try {
    $conn = new PDO("sqlsrv:Server=$servername;Database=$dbname", null, null, array(
        PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8
    ));
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (empty($email)) {
        logLoginAttempt($conn, $email, null, 0); // Log login attempt with status 0
        echo 'error';
        exit;
    }

    $query = "SELECT phone_number FROM Users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        logLoginAttempt($conn, $email, null, 0); // Log login attempt with status 0
        echo 'error';
        exit;
    }

    $phoneNumber = $result['phone_number'];

    // Generate kode rahasia dan simpan di session
    $secretCode = rand(100000, 999999);
    $_SESSION['wa_secret_code'] = $secretCode;
    $_SESSION['email'] = $email;

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
        logLoginAttempt($conn, $email, $secretCode, 0); // Log login attempt with status 0
        echo 'error';
    } else {
        file_put_contents('dummy_wa_log.txt', "To: $phoneNumber - Message: $message - API Response: $response" . PHP_EOL, FILE_APPEND);
        logLoginAttempt($conn, $email, $secretCode, 0); // Log login attempt with status 1
        echo 'success';
    }

    curl_close($ch);
}

function logLoginAttempt(PDO $conn, $email, $code, $status)
{
    try {
        $query = "INSERT INTO hUserLogin (login_date, email, code, status) VALUES (GETDATE(), :email, :code, :status)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':code', $code, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        file_put_contents('dummy_wa_log.txt', "Error logging login attempt: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    }
}

?>

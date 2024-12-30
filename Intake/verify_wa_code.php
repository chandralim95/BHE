<?php
session_start();

$servername = "LAPTOP-DLPOU7KE";
$username = "LAPTOP-DLPOU7KE\\ASUS";
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
    $code = $_POST['code'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    // Validasi CSRF token
    if (empty($csrfToken) || $csrfToken !== $_SESSION['csrf_token']) {
        echo 'csrf_error';
        exit;
    }

    // Validasi kode rahasia
    if (isset($_SESSION['wa_secret_code'])) {
        file_put_contents('error_log.txt', 'Session code: ' . ($_SESSION['wa_secret_code'] ?? 'not set') . PHP_EOL, FILE_APPEND);
        if($code === strval($_SESSION['wa_secret_code'])){
            file_put_contents('error_log.txt', 'Berhasil masuk' . PHP_EOL, FILE_APPEND);
            unset($_SESSION['wa_secret_code']); // Hapus setelah success

            $email = $_SESSION['email'] ?? '';
            if (!empty($email)) {
                file_put_contents('error_log.txt', 'Email: ' . ($_SESSION['email'] ?? 'not set') . PHP_EOL, FILE_APPEND);
                try {
                    $query = "UPDATE hUserLogin SET status = 1 WHERE email = :email AND code = :code AND status = 0";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
                    $stmt->execute();
    
                    if ($stmt->rowCount() > 0) {
                        echo 'success';
                    } else {
                        echo 'update_error';
                    }
                } catch (PDOException $e) {
                    file_put_contents('error_log.txt', "Database Error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                    echo 'db_error';
                }
            } else {
                echo 'email_missing';
            } 
        } else {
            echo 'Kode verifikasi salah.';
            exit;
        }
    } else {
        echo 'error';
    }
}
?>

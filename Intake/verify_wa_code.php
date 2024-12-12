<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    //Validasi CSRF token
    if (empty($csrfToken) || $csrfToken !== $_SESSION['csrf_token']) {
        echo 'csrf_error';
        exit;
    }

    // Validasi kode rahasia
    if (isset($_SESSION['wa_secret_code']) && $code === strval($_SESSION['wa_secret_code'])) {
        unset($_SESSION['wa_secret_code']); //Hapus setelah success
        echo 'success';
    } else {
        echo 'error';
    }
}
?>

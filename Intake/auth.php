<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? null;
    $email = $_POST['email'] ?? null;

    // Validasi password
    if ($password === '123' && $email === 'test@gmail.com') {
        echo 'success'; 
    } else {
        echo 'fail'; 
    }
} else {
    http_response_code(405); 
    echo 'Method Not Allowed';
}

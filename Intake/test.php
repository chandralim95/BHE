<?php

session_start();

// URL API
$url = "https://api.watzap.id/v1/send_message";

// Ambil secret code dari session
if (!isset($_SESSION['wa_secret_code'])) {
    echo "Error: Secret code tidak ditemukan.";
    exit;
}

$secretCode = $_SESSION['wa_secret_code'];

// Data yang akan dikirimkan dalam bentuk JSON
$data = [
    "api_key" => "HKLACYKAMEKPZDCK",
    "number_key" => "O3j5viLxzHydtOEy",
    "phone_no" => "628977168889",
    "message" => "Secret code: $secretCode",
    "wait_until_send" => "1"
];

// Inisialisasi cURL
$ch = curl_init($url);

// Atur opsi cURL
curl_setopt($ch, CURLOPT_POST, true); // Metode POST
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Mengembalikan respons sebagai string
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json", // Header untuk menunjukkan data dalam format JSON
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Kirimkan data JSON

// Eksekusi permintaan
$response = curl_exec($ch);

// Cek apakah terjadi error
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
} else {
    // Tampilkan respons dari server
    echo "Response: " . $response;
}

// Tutup cURL
curl_close($ch);

?>

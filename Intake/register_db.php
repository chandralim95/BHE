<?php
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
?>

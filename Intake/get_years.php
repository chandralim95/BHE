<?php
session_start();

if (!isset($_SESSION['csrf_token'])) {
    header('Location: index.php');
    exit;
}

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

if (isset($_GET['university_id']) && !empty($_GET['university_id'])) {
    $universityId = $_GET['university_id'];
    $query = "SELECT DISTINCT YearID, Year FROM Years WHERE UniversityID = :universityId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':universityId', $universityId, PDO::PARAM_INT);
    $stmt->execute();
    $years = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $years = [];
}

header('Content-Type: application/json');
echo json_encode($years);

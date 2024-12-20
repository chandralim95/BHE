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

$majors = [];

if (isset($_GET['university_id']) && !empty($_GET['university_id']) && isset($_GET['year_id']) && !empty($_GET['year_id'])) {
    $universityId = $_GET['university_id'];
    $yearId = $_GET['year_id'];

    $query = "SELECT DISTINCT m.MajorID, m.MajorName 
              FROM Majors m
              JOIN Students s ON m.MajorID = s.MajorID
              WHERE s.UniversityID = :universityId AND s.YearID = :yearId";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':universityId', $universityId, PDO::PARAM_INT);
    $stmt->bindParam(':yearId', $yearId, PDO::PARAM_INT);
    $stmt->execute();
    $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');
echo json_encode($majors);

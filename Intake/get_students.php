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

$students = [];

if (isset($_GET['university_id']) && !empty($_GET['university_id'])) {
    $universityId = $_GET['university_id'];
    $query = "SELECT 
        s.StudentID, 
        s.StudentName, 
        s.StudentNIM, 
        m.MajorName, 
        u.UniversityName 
    FROM Students s
    JOIN Majors m ON s.MajorID = m.MajorID
    JOIN Universities u ON s.UniversityID = u.UniversityID
    WHERE u.UniversityID = :universityId";

    //Kalau tahun juga dipilih
    if (isset($_GET['year_id']) && !empty($_GET['year_id'])) {
        $yearId = $_GET['year_id'];
        $query .= " AND s.YearID = :yearId";
    }

    if (isset($_GET['major_id']) && !empty($_GET['major_id'])) {
        $majorId = $_GET['major_id'];
        $query .= " AND s.MajorID = :majorId";
    }

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':universityId', $universityId, PDO::PARAM_INT);

    if (isset($yearId)) {
        $stmt->bindParam(':yearId', $yearId, PDO::PARAM_INT);
    }
    if (isset($majorId)) {
        $stmt->bindParam(':majorId', $majorId, PDO::PARAM_INT);
    }

    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');
echo json_encode($students);

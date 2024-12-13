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

$query = "SELECT UniversityID, UniversityName FROM Universities";

$stmt = $conn->query($query);

if ($stmt === false) {
    die("Error executing query.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <title>Dashboard</title>
    <style>
        body {
            background-color: gray;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }

        .dropdown-container {
            gap: 2rem;
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 60%;
            margin-bottom: 20px;
        }

        .table-container {
            width: 80%;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
        }
    </style>
</head>

<body>
    <h1 style="color: white;">Dashboard</h1>

    <div class="dropdown-container">
        <select class="form-select" id="dropdown1" aria-label="Dropdown 1">
            <option value="" selected>Pilih Universitas</option>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?= $row['UniversityID'] ?>">
                    <?= $row['UniversityName'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select class="form-select" id="dropdown2" aria-label="Dropdown 2">
            <option value="" selected>Pilih Tahun</option>
        </select>

        <select class="form-select" id="dropdown3" aria-label="Dropdown 3">
            <option value="" selected>Pilih Jurusan</option>
        </select>
    </div>

    <div class="table-container">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Jurusan</th>
                    <th>Tahun</th>
                    <th>Universitas</th>
                </tr>
            </thead>
            <tbody id="student-table">
                
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>

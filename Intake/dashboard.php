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

$query = "SELECT 
    s.StudentID, 
    s.StudentName, 
    s.StudentNIM, 
    m.MajorName, 
    u.UniversityName 
FROM Students s
JOIN Majors m ON s.MajorID = m.MajorID
JOIN Universities u ON s.UniversityID = u.UniversityID";

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
            <?php
            $universityQuery = "SELECT UniversityID, UniversityName FROM Universities";
            $universityStmt = $conn->query($universityQuery);
            while ($row = $universityStmt->fetch(PDO::FETCH_ASSOC)) : ?>
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
                    <th>Universitas</th>
                </tr>
            </thead>
            <tbody id="student-table">
                <?php $i = 1;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($row['StudentName']) ?></td>
                        <td><?= htmlspecialchars($row['StudentNIM']) ?></td>
                        <td><?= htmlspecialchars($row['MajorName']) ?></td>
                        <td><?= htmlspecialchars($row['UniversityName']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        //Kalau dropdown 1, dropdown 2, atau dropdown 3 dipilih
        $('#dropdown1, #dropdown2, #dropdown3').on('change', function() {
            const universityId = $('#dropdown1').val();
            const yearId = $('#dropdown2').val();
            const majorId = $('#dropdown3').val();

            //Reset tabel mahasiswa
            $('#student-table').html('<tr><td colspan="5">No data found</td></tr>');

            //Cek jika dropdown 1 yang berubah
            if ($(this).attr('id') === 'dropdown1' && universityId) {
                $.ajax({
                    url: 'get_years.php',  //Memanggil get_years.php untuk mendapatkan tahun
                    type: 'GET',
                    data: {
                        university_id: universityId
                    },
                    success: function(response) {
                        const yearDropdown = $('#dropdown2');
                        yearDropdown.empty().append('<option value="" selected>Pilih Tahun</option>');
                        if (response.length > 0) {
                            response.forEach((year) => {
                                const option = `<option value="${year.YearID}">${year.Year}</option>`;
                                yearDropdown.append(option);
                            });
                        } else {
                            yearDropdown.append('<option value="">No data available</option>');
                        }
                    },
                    error: function() {
                        alert('Failed to fetch years.');
                    }
                });
            }

            //Cek jika dropdown 2 berubah
            if (universityId && $(this).attr('id') === 'dropdown2' && yearId) {
                $.ajax({
                    url: 'get_majors.php',  //Memanggil get_majors.php untuk mendapatkan jurusan
                    type: 'GET',
                    data: {
                        university_id: universityId,
                        year_id: yearId
                    },
                    success: function(response) {
                        const majorDropdown = $('#dropdown3');
                        majorDropdown.empty().append('<option value="" selected>Pilih Jurusan</option>');
                        if (response.length > 0) {
                            response.forEach((major) => {
                                const option = `<option value="${major.MajorID}">${major.MajorName}</option>`;
                                majorDropdown.append(option);
                            });
                        } else {
                            majorDropdown.append('<option value="">No data available</option>');
                        }
                    },
                    error: function() {
                        alert('Failed to fetch majors.');
                    }
                });
            }

            const requestData = {
                university_id: universityId
            };
            if (yearId) {
                requestData.year_id = yearId;
            }
            if (majorId) {
                requestData.major_id = majorId;
            }

            console.log('Request Data:', requestData);

            $.ajax({
                url: 'get_students.php',
                type: 'GET',
                data: requestData,
                success: function(response) {
                    const studentTable = $('#student-table');
                    studentTable.empty();
                    if (response.length > 0) {
                        response.forEach((student, index) => {
                            const row = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${student.StudentName}</td>
                                    <td>${student.StudentNIM}</td>
                                    <td>${student.MajorName}</td>
                                    <td>${student.UniversityName}</td>
                                </tr>
                            `;
                            studentTable.append(row);
                        });
                    } else {
                        studentTable.append('<tr><td colspan="5">No data found</td></tr>');
                    }
                },
                error: function() {
                    alert('Failed to fetch students.');
                }
            });
        });
    });
</script>


</body>

</html>
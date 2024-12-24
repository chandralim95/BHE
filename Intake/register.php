<?php
include_once('register_db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    if (empty($email) || empty($phone_number)) {
        echo json_encode(["status" => "error", "message" => "Email dan nomor WhatsApp wajib diisi"]);
        exit;
    }

    try {
        $query = "INSERT INTO Users (email, phone_number) VALUES (:email, :phone_number)";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);

        $stmt->execute();

        echo json_encode(["status" => "success", "message" => "Registrasi berhasil!"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
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
    <title>BHE</title>
</head>
<body style="background-color: gray">
    <section class="loginSection">
        <div class="login-container">
            <div class="formBx">
                <div class="form signinForm">
                    <form id="singIn" method="POST" action="register.php">
                        <div id="apppendhere"></div>
                        <h3 style="color: gray; text-align: center;">Register</h3>
                        <label class="text text-danger" id="loginemailvalidation"></label>
                        <input id="login-email" type="text" name="email" placeholder="Email">
                        <label class="text text-danger" id="loginpasswordvalidation"></label>
                        <div class="input-group d-flex flex-nowrap">
                            <span class="input-group-text h-100">+62</span>
                            <input type="text" aria-label="phone" class="form-control" name="phone_number" placeholder="WhatsApp Number">
                        </div>

                        <input type="submit" value="Register" id="btn-register" class="btn btn-primary" style="background-color: gray">
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

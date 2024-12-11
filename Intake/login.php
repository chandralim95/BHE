<?php
// include_once('ConFig/ConFig_DBhelpers.php');
// session_start();
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
                    <form id="singIn">
                        <div id="apppendhere"></div>
                        <h3 style="color: gray; text-align: center;">Login</h3>
                        <label class="text text-danger" id="loginemailvalidation"></label>
                        <input id="login-email" type="text" name="loginemail" placeholder="Email">
                        <label class=" text text-danger" id="loginpasswordvalidation"></label>
                        <input id="login-password" type="password" name="loginpassword" placeholder="Password">
                        <input type="button" value="Login" id="btn-login" class="btn btn-primary" style="background-color: gray">
                    </form>
                </div>
            </div>
        </div>
    </section>

</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
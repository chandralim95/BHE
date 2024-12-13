<?php
session_start();

function generateCSRFToken()
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

if (!isset($_SESSION['csrf_token'])) {
    $csrfToken = generateCSRFToken();
} else {
    $csrfToken = $_SESSION['csrf_token'];
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

    <style>
        body {
            background-color: gray;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .loginSection,
        .waAuthSection {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 500px;
        }

        .formBx {
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            background: #fff;
            z-index: 1000;
            display: grid;
            justify-content: center;
            align-items: center;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .waAuthSection .login-container .formBx {
    position: absolute;
    top: 28%;
    left: 33%;
    border-radius: 20px;
    width: 30%;
    height: 50%;
    background: #fff;
    z-index: 1000;
    display: grid
;
    justify-content: center;
    align-items: center;
}
    </style>
</head>

<body>
    <section class="loginSection">
        <div class="login-container">
            <div class="formBx">
                <div class="form signinForm">
                    <form id="signIn">
                        <h3 style="color: gray; text-align: center;">Login</h3>
                        <label class="text text-danger" id="loginemailvalidation"></label>
                        <input id="login-email" type="text" name="loginemail" placeholder="Email">
                        <input type="button" value="Login" id="btn-login" class="btn btn-primary" style="background-color: gray">
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="waAuthSection" style="display: none;">
        <div class="login-container">
            <div class="formBx">
                <h1>Authentication via WhatsApp</h1>
                <p>Please enter the secret number sent to your WhatsApp:</p>
                <div class="input-wrapper">
                    <input type="text" id="wa-code-input" placeholder="Enter Secret Number" maxlength="6">
                    <input type="hidden" id="csrf-token" value="<?php echo $csrfToken; ?>">
                </div>
                <button id="btn-verify-wa" class="btn btn-primary">Verify</button>
                <p id="wa-auth-error" class="text-danger"></p>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#btn-login').click(function() {
                var email = $('#login-email').val();

                if (email === '') {
                    $('#loginemailvalidation').text('Email is required.');
                } else {
                    $.ajax({
                        url: 'send_wa_code.php',
                        method: 'POST',
                        data: {
                            email: email
                        },
                        success: function(response) {
                            if (response === 'success') {
                                $('.loginSection').hide();
                                $('.waAuthSection').show();
                            } else {
                                $('#loginemailvalidation').text('Failed to send WhatsApp code.');
                            }
                        },
                        error: function() {
                            alert('An error occurred');
                        }
                    });
                }
            });

            $('#btn-verify-wa').click(function() {
                var waCode = $('#wa-code-input').val();
                var csrfToken = $('#csrf-token').val();

                if (waCode === '') {
                    $('#wa-auth-error').text('Secret number is required.');
                } else {
                    $.ajax({
                        url: 'verify_wa_code.php',
                        method: 'POST',
                        data: {
                            code: waCode,
                            csrf_token: csrfToken
                        },
                        success: function(response) {
                            if (response === 'success') {
                                window.location.href = 'dashboard.php';
                            } else {
                                $('#wa-auth-error').text('Invalid secret number. Please try again.');
                            }
                        },
                        error: function() {
                            alert('An error occurred. Please try again.');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>

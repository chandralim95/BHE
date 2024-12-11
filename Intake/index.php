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

    <section class="discordVerificationSection" style="display: none;">
        <div class="container">
            <h1>Authentication</h1>
            <div class="input-wrapper">
                <input type="text" id="nim-input" placeholder="NIM atau FormID" oninput="validateInput(this)">
                <input type="hidden" id="csrf-token" value="<?php echo $csrfToken; ?>">
            </div>
            <div class="result" style="display: none;">
                <input type="text" id="data-result" readonly>
                <button class="copy-button" onclick="copyText()">Copy</button>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    
    <script>
        $(document).ready(function () {
            $('#btn-login').click(function () {
                var email = $('#login-email').val();
                var password = $('#login-password').val();
                
                if (email == '' || password == '') {
                    $('#loginemailvalidation').text('Email and password are required.');
                } else {
                    $.ajax({
                        url: 'auth.php',
                        method: 'POST',
                        data: {
                            email: email,
                            password: password
                        },
                        success: function (response) {
                            if (response === 'success') {
                                $('.loginSection').hide();
                                $('.discordVerificationSection').show();
                            } else {
                                $('#loginemailvalidation').text('Invalid credentials. Please try again.');
                            }
                        },
                        error: function () {
                            alert('An error occurred. Please try again.');
                        }
                    });
                }
            });

            $('#nim-input').on('input', function () {
                var nim = $(this).val();
                var nimLength = nim.length;
                var csrfToken = $('#csrf-token').val();

                if (nimLength === 8 || nimLength === 10) {
                    var searchType = nimLength === 8 ? 'form_number' : 'nim';

                    $.ajax({
                        url: 'search.php',
                        method: 'GET',
                        data: {
                            searchType: searchType,
                            nim: nim,
                            csrf_token: csrfToken
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function (data) {
                            if (data !== '') {
                                $('#data-result').val(data);
                                $('.result').show();
                            } else {
                                $('.result').hide();
                            }
                        }
                    });
                } else {
                    $('.result').hide();
                }
            });
        });

        function validateInput(input) {
            // Hapus karakter selain angka
            input.value = input.value.replace(/\D/g, '');
            
            // Batasi panjang input menjadi maksimal 10 digit
            if (input.value.length > 10) {
                input.value = input.value.slice(0, 10);
            }
        }

        function copyText() {
            var resultInput = document.getElementById("data-result");
            resultInput.select();
            document.execCommand("copy");

            var copyButton = document.querySelector(".copy-button");

            if (document.getSelection().toString() === resultInput.value) {
                copyButton.innerText = "Copied!";
                copyButton.disabled = true;
            } else {
                copyButton.innerText = "Copy";
                copyButton.disabled = false;
            }
        }
    </script>
</body>

</html>

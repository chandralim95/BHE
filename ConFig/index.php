<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="0;url=../">
    <title>Redirecting...</title>
</head>

<body>
    <p>If you are not redirected, <a href="../">click here</a>.</p>
    
    <?php
    header("Location: ../");
    exit;
    ?>
</body>

</html>
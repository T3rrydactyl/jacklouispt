<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();

// Include functions and connect to the database using PDO MySQL
include 'functions.php';
$pdo = pdo_connect_mysql();
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
    <title>jacklouispt - Account login</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles-global.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php setFavicon(); ?>

    <style>

    .section {
      height:70vh;
    }


        }
    </style>
</head>
<body>
    <!-- Top bar -->
    <?php displayTopBar(); ?>


    <div class="section" style="margin: 0 auto; border-radius: 5px;">
<div class="login-register">
    <form action="authenticate.php" method="post">
        <h2 style="text-align: center; color: #333; margin-bottom: 20px;">Account Login</h2>
        <?php
            if (isset($_SESSION['login_error'])) {
                echo '<p style="color: red;">' . $_SESSION['login_error'] . '</p>';
                unset($_SESSION['login_error']); // Clear the error message after displaying it
            }
        ?>
        <p>Forgotten your password? You can <a href="password-reset-form.php" class="login">reset your password</a>.</p>
        <label for="username">
            <i class="fas fa-user"><img src="images/account/username.png"></i>
        </label>
        <input type="text" name="username" placeholder="Username" id="username" required>
        <label for="password">
            <i class="fas fa-lock"><img src="images/account/password.png"></i>
        </label>
        <input type="password" name="password" placeholder="Password" id="password" required>
        <input type="submit" value="Login">
    </form>
</div>
</div>

        <?php
          displayFooter();
        ?>


    <script>

    </script>
</body>
</html>

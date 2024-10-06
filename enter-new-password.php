<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// Include functions and connect to the database using PDO MySQL
include "functions.php";
session_start();


$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'jackloui_dbUSER';
$DATABASE_PASS = 'murkynight77';
$DATABASE_NAME = 'jackloui_phplogin';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if (isset($_GET['email'], $_GET['code'])) {
	if ($stmt = $con->prepare('SELECT * FROM Account WHERE email = ? AND password_reset_code = ?')) {
		$stmt->bind_param('ss', $_GET['email'], $_GET['code']);
		$stmt->execute();
		// Store the result so we can check if the account exists in the database.
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			// Account exists with the requested email and code.
      $_SESSION['password-reset-email'] = $_GET['email'];
      $_SESSION['password_reset_code'] = $_GET['code'];
		} else {
      header("Location: page-not-found.php");
      exit();
		}
	}
}
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
    <title>JackLouisPT - Set New Password</title>

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
    </style>
</head>




<body>

    <!-- Top bar -->
    <!-- Top bar -->
    <?php displayTopBar(); ?>


    <div class="section" style="margin: 0 auto; border-radius: 5px;">
      <div class="login-register">
  <form action="set-new-password.php" method="post">
    <h2 style="text-align: center; color: #333; margin-bottom: 20px;">Set New Password</h2>
    <p>In order to complete the Password Reset process, please enter the new Password in twice to confirm.</p>
    <label for="password1">
      <i class="fas fa-user"><img src="images/account/password.png"></i>
    </label>
    <input type="password" name="password1" placeholder="Enter new password" id="password1" required>
    <label for="password2">
      <i class="fas fa-lock"><img src="images/account/password.png"></i>
    </label>
    <input type="password" name="password2" placeholder="Confirm new password" id="password2" required>
    <input type="submit" value="Submit Request">
  </form>
</div>
    </div>

        <?php
          displayFooter();
        ?>



</body>
</html>

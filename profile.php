<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "functions.php";
$pdo = pdo_connect_mysql();

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["name"];

$query = "SELECT email, fitness_level FROM Account WHERE username = :username";
$stmt = $pdo->prepare($query);
$stmt->bindParam(":username", $username, PDO::PARAM_STR);
$stmt->execute();
$accountInfo = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array
$email = $accountInfo['email']; // Access the 'email' column from the fetched row
$fitness_level = $accountInfo['fitness_level'];


?>
<!DOCTYPE html>
<html>
<head>
    <title>jacklouispt - My Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles-global.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <!-- Top bar -->
  <?php displayTopBar(); ?>

    <div class="page-heading">
        <h1>My Profile</h1>
    </div>

    <div class="section">
    <div class="box">
      <h2>Personal Details</h2>
      <p>___</p>
      <h3>Username:</h3>
      <p><?php echo $username; ?></p>
      <h3>Email:</h3>
      <p><?php echo $email; ?></p>
      <h3>Password:</h3>
      <a href="/password-reset-form.php">Reset Password ></a>

      <br>
      <h2>Your Fitness Level</h2>
      <p>___</p>
      <p>Want to find out more about what this means? Simply visit the dedicated page by clicking <a href="select-fitness-level.php">here</a>.</p>
      <form action="update-fitness.php" method="POST">
          <h3>Select your level of fitness:<h3>
          <select style="font-size: 16px; font-family: Avenir;" id="fitnessLevel" name="fitnessLevel" required>
              <option value="beginner">Beginner</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
          </select><br><br>
          <input type="submit" style="background-color: #E10101; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;" value="Submit">
      </form>
    </div>
  </div>




    <?php displayFooter(); ?>

</body>
</html>

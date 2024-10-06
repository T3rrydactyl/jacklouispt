<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// Include functions and connect to the database using PDO MySQL
include "functions.php";
$pdo = pdo_connect_mysql();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["name"];

?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
    <title>jacklouispt - Calorie Calculator</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles-global.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php setFavicon(); ?>
    <style>


    </style>

</head>




<body>

  <!-- Top bar -->
  <?php displayTopBar(); ?>

  <div class="section">
  <div class="page-heading">
    <h1>Calorie Calculator</h1>
  </div>

  <iframe src="https://www.mealpro.net/calorie/?color=333333" frameborder="0" width="100%" height="1000" style="max-width: 100%"></iframe>
  </div>








<?php displayFooter(); ?>







</body>

</html>

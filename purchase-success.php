<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
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

if (!isset($_SESSION["productIDs"])) {
    header("Location: show-cart.php");
    exit();
}

$username = $_SESSION["name"];
$account_id = getAccountIDFromUsername($pdo, $username);

foreach ($_SESSION["productIDs"] as $product_id) {
  $queryGrantLicense = "INSERT INTO License (account_id, product_id) VALUES (:account_id, :product_id)";
  $stmtGrantLicense = $pdo->prepare($queryGrantLicense);
  $stmtGrantLicense->bindParam(":account_id", $account_id, PDO::PARAM_INT);
  $stmtGrantLicense->bindParam(":product_id", $product_id, PDO::PARAM_INT);
  $stmtGrantLicense->execute();
}

//Empties the shopping cart
$_SESSION['cart'] = array();
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
    <title>jacklouispt - Your Fitness Level</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles-global.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php setFavicon(); ?>

    <style>

    .section {
      height: auto;
    }

    </style>
</head>




<body>

    <!-- Top bar -->
    <?php displayTopBar(); ?>





  <div class="section">
  <div class="box">

      <div class="page-heading">
      <h1>Checkout Success!</h1>
    </div>

      <h2>Access Has Been Granted</h2>
      <p>___</p>
      <p>Your account has now been granted access to all digital content from the:<br>
      <?php foreach ($_SESSION["productIDs"] as $product_id) {
        $queryGetCategoryFromProductID = "SELECT name FROM Product WHERE product_id = :product_id";
        $stmtGetCategoryFromProductID = $pdo->prepare($queryGetCategoryFromProductID);
        $stmtGetCategoryFromProductID->bindParam(":product_id", $product_id, PDO::PARAM_INT);
        $stmtGetCategoryFromProductID->execute();
        $product_name = $stmtGetCategoryFromProductID->fetchColumn();
        echo "- " . $product_name . "<br>";
      } ?> Get started by visiting your <a href="dashboard.php">User Dashboard</a>.</p>





    <img src="images/purchase-success/image.jpg" style="width: 100%; margin-top: 10px; margin-bottom: 15px;">


  </div>
  </div>





  <?php displayFooter(); ?>



</body>
</html>

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
      <h1>Your Fitness Level</h1>
    </div>

      <h2>Programme Difficulty</h2>
      <p>___</p>
      <p>When you decide to embark on one of our programmes, we will present you with the ones tailored to your selected level. You have the freedom to modify this preference whenever you wish within the 'My Profile' section.</p>


    <form action="update-fitness.php" method="POST">
        <label for="fitnessLevel">Select your level of fitness:</label><br>
        <select style="font-size: 16px; font-family: Avenir;" id="fitnessLevel" name="fitnessLevel" required>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
        </select><br><br>
        <input type="submit" style="background-color: #E10101; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;" value="Submit">
    </form>

    <h2>Beginner Programmes</h2>
    <p>___</p>
    <p>For those just starting out, Beginner Programmes typically span 3 or 4 days per week. These programmes focus on a lower volume for each muscle group. This approach acknowledges the accelerated growth beginners experience, allowing ample time for recovery. The choice between different training splits boils down to the number of training days per week and personal preference.</p>

    <h2>Intermediate Programmes</h2>
    <p>___</p>
    <p>For those at the Intermediate level, a recommended programme involves 4 or 5 days of training per week. These programmes entail slightly more volume than beginner routines, as intermediate individuals require slightly less recovery time. As an intermediate user, you will also have the ability to start an Advanced Programme that follows a 5-day split. If a 5-day training schedule suits you, opt for one of these Advanced Plans while adjusting the sets per muscle group if recovery becomes a concern.</p>
    <p>Some programmes will be suitable for both Intermediate and Advanced users. We will only recommend programmes we think are appropriate for your level.</p>

    <h2>Advanced Programmes</h2>
    <p>___</p>
    <p>For those at the Advanced Level, it's advisable to follow a 5 or 6-day training programme. These advanced programmes offer the highest volume among the different plans. As you make progress, your recovery time tends to decrease. As an advanced user, you will also have the option to start an Intermediate programme which typically involves 4 days of training per week. This is a viable option if you find yourself limited to 4 days. However, you should consider adding a few extra sets to each muscle group if you decide to take this route.</p>

    <img src="images/select-fitness-level/image.jpg" style="width: 100%; margin-top: 10px; margin-bottom: 15px;">

    <h2>Choosing Split & Rep Count - All Levels</h2>
    <p>___</p>
    <p>No split option that we recommend for your level is better than another. It is all down to how many days per week you can train and the split you enjoy best. </p>
    <p>Each exercise will be accompanied by a recommended number of sets and rep ranges. For instance, if a rep range of 6-8 is suggested for Incline Presses, your goal at first is to select a weight that leads to muscle failure at around 6 reps. As the weeks progress, aim for 7 reps, then 8, gradually increasing the weight as you maintain this pattern. This approach, known as progressive overload, ensures ongoing improvement.</p>
    <p>Exercise order is important as your muscles will be stronger at the start of the workout and fatigue towards the end of the workout.</p>

  </div>


  </div>





  <?php displayFooter(); ?>



</body>
</html>

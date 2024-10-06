<?php

// We need to use sessions, so you should always start sessions using the below code.
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include functions and connect to the database using PDO MySQL
include "functions.php";
$pdo = pdo_connect_mysql();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["name"];

// Fetch fitness level
$queryFitnessLevel =
    "SELECT fitness_level FROM Account WHERE Account.username = :username";

$stmtFitnessLevel = $pdo->prepare($queryFitnessLevel);
$stmtFitnessLevel->bindParam(":username", $username, PDO::PARAM_STR);
$stmtFitnessLevel->execute();
$fitness_level = $stmtFitnessLevel->fetchColumn();

if ($fitness_level === null) {
    header("Location: select-fitness-level.php");
    exit();
}

// Fetch active programmes for the logged-in user
$queryActive =
    "SELECT start_date, name, days, ProgrammeSession.session_id FROM ProgrammeSession, Programme, Account WHERE ProgrammeSession.programme_id = Programme.programme_id AND ProgrammeSession.account_id = Account.account_id AND Account.username = :username AND ProgrammeSession.completed = 0";
$stmtActive = $pdo->prepare($queryActive);
$stmtActive->bindParam(":username", $username, PDO::PARAM_STR);
$stmtActive->execute();
$activePrograms = $stmtActive->fetchAll(PDO::FETCH_ASSOC);

// Fetch completed programmes for the logged-in user
$queryArchive =
    "SELECT start_date, name, days, ProgrammeSession.session_id FROM ProgrammeSession, Programme, Account WHERE ProgrammeSession.programme_id = Programme.programme_id AND ProgrammeSession.account_id = Account.account_id AND Account.username = :username AND ProgrammeSession.completed = 1";
$stmtArchive = $pdo->prepare($queryArchive);
$stmtArchive->bindParam(":username", $username, PDO::PARAM_STR);
$stmtArchive->execute();
$archivePrograms = $stmtArchive->fetchAll(PDO::FETCH_ASSOC);

// Fetch programme access
$license = 2; // Primary key of Programme table - 2 is a programme_id of a Fat Loss Programme. Access to one fat loss => access to all fat loss
$fatLossLicenseCount = getLicenseCount($pdo, $username, $license);

$license = 1; // Primary key of Licenses table - 1 uniquely idenitifes Muscle Growth
$muscleGrowthLicenseCount = getLicenseCount($pdo, $username, $license);
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
    <title>jacklouispt - User Dashboard</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles-global.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php setFavicon(); ?>

    <style>


        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .section a {
            margin: 0 5px;
        }

        .wrapper img {
          max-width: 500px;
        }



        /* Media queries for medium viewports */
        @media (max-width: 1500px) {

          .banner img {
              height: 400px;
          }

        }


        /* Media queries for smaller viewports */
        @media (max-width: 1024px) {

            #desktop-dashboard-img img {
                display: none;
            }

        }



        }

    </style>
</head>




<body>

    <!-- Top bar -->
    <?php displayTopBar(); ?>

    <div class="page-heading">
    <h1>User Dashboard</h1>
  </div>

  <div class="wrapper">

    <div class="section">
      <h2>Current Programme</h2>
      <?php if (count($activePrograms) > 0) {
          echo "<p>___</p>";
          foreach ($activePrograms as $program) {
              echo "<p>" .
                  $program["name"] .
                  " [" .
                  $program["days"] .
                  " days] - Started " .
                  $program["start_date"] .
                  "</p>";
              echo '<a href="programme-overview.php?session_id=' . $program["session_id"] . '">Resume ></a> <a href="javascript:void(0);" onclick="confirmDelete(' . $program["session_id"] . ')">Remove ></a>';
              echo "<p><br></p>";
          }
      } else {
          echo "<p>___<br>You do not have any active programmes. <br>Go to the Programme Launcher to start one.</p>";
      } ?>

      <h2>Archived Programmes</h2>
      <?php if (count($archivePrograms) > 0) {
          echo "<p>___</p>";
          foreach ($archivePrograms as $program) {
              echo "<p>" .
                  $program["name"] .
                  " [" .
                  $program["days"] .
                  " days] - Started " .
                  $program["start_date"] .
                  "</p>";
              echo '<a href="view-archived-programme.php?session_id=' . $program["session_id"] . '">View Activity Log ></a><br>';
              echo "<p><br></p>";
          }
      } else {
          echo "<p>___<br>You have not completed any programmes.</p>";
      } ?>
    </div>
    <div id="desktop-dashboard-img">
    <img src="images/dashboard/gym.jpg">
  </div>
  </div>



  <div class="box">

    <div class="section">
      <h2>Programme Launcher</h2>
      <p>___</p>
      <p>Choose your desired programme below and step into a world of boundless strength, extraordinary results, and unstoppable motivation. The programmes displayed are all at an equivalent level of difficulty to your selected fitness level. Your difficulty level is currently set to
        <?php
          if ($fitness_level == 'beginner') {
            echo "Beginner";
          } elseif ($fitness_level == 'intermediate') {
            echo "Intermediate";
          } else {
            echo "Advanced";
          }
        ?>.
      </p>
    </div>
  </div>



  <div class="banner">
    <img src="images/dashboard/fat-loss.jpg">
    <div class="centered">
        <h2>Fat Loss Programme</h2>
        <p>Rewrite Your Story: Unleash the New, Fitter You!</p>
        <?php if ($fatLossLicenseCount > 0 && count($activePrograms) > 0) {
            echo "<p>You have access to this programme but you must complete or remove your current active session before starting a new one.</p>";
        } elseif ($fatLossLicenseCount > 0 && count($activePrograms) === 0) {
            $programs = fetchProgramsForLevelAndCategory($pdo, $fitness_level, 'Fat Loss');
            foreach ($programs as $program) {
                echo '<a href="launch-programme.php?programme_id=' . $program["programme_id"] . '&category=Fat%20Loss">' . $program["name"] . ' [' . $program["days"] . ' Day] ></a><br>';
            }
        } else {
            echo '<a href="cart.php?action=add&product_id=2">Add to cart ></a>';
        } ?>
    </div>
</div>

<div class="banner">
  <img src="images/dashboard/muscle-gain.jpg">
  <div class="centered">
      <h2>Muscle Growth Programme</h2>
      <p>Unleash Your Inner Beast: Unlock Maximum Strength and Size!</p>
      <?php if ($muscleGrowthLicenseCount > 0 && count($activePrograms) > 0) {
          echo "<p>You have access to this programme but you must complete or remove your current active session before starting a new one.</p>";
      } elseif ($muscleGrowthLicenseCount > 0 && count($activePrograms) === 0) {
          $programs = fetchProgramsForLevelAndCategory($pdo, $fitness_level, 'Muscle Growth');
          foreach ($programs as $program) {
              echo '<a href="launch-programme.php?programme_id=' . $program["programme_id"] . '&category=Muscle%20Growth">' . $program["name"] . ' [' . $program["days"] . ' Day] ></a><br>';
          }
      } else {
          echo '<a href="cart.php?action=add&product_id=1">Add to cart ></a>';
      } ?>
  </div>
</div>





  <?php displayFooter(); ?>


  <script>
  function confirmDelete(sessionId) {
      if (confirm("Are you sure you want to remove this programme? This action cannot be undone.")) {
          window.location.href = "remove-programme.php?session_id=" + sessionId;
      }
  }
  </script>
</body>
</html>

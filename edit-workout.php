<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
$session_id = intval($_GET["session_id"]);
$day_number = $_GET["day_number"];


// Confirm the session is incomplete and it belongs to the logged in user.
$queryValidSession =
    "SELECT ProgrammeSession.programme_id, session_id, Account.account_id, username, name, days FROM Programme, ProgrammeSession, Account WHERE Programme.programme_id = ProgrammeSession.programme_id AND ProgrammeSession.session_id = :session_id AND Account.username = :username AND completed = 0";
$stmtValidSession = $pdo->prepare($queryValidSession);
$stmtValidSession->bindParam(":username", $username, PDO::PARAM_STR);
$stmtValidSession->bindParam(":session_id", $session_id, PDO::PARAM_INT);
$stmtValidSession->execute();
$sessionDetail = $stmtValidSession->fetch(PDO::FETCH_ASSOC);

$programmeName = $sessionDetail["name"];
$programmeID = $sessionDetail["programme_id"];
$programmeDays = $sessionDetail["days"];
$fitness_level = getDifficultyFromProgrammeID($pdo, $programmeID);



if ($stmtValidSession->rowCount() == 0) {
    // No matching sessions are found for the logged-in user
    echo "No session details found.";
} else {
    // Fetch existing exercise logs for the given session and day
    $queryExistingLogs = "SELECT exercise_number, set_number, weight, reps, note FROM ExerciseLog WHERE session_id = :session_id AND day_number = :day_number";
    $stmtExistingLogs = $pdo->prepare($queryExistingLogs);
    $stmtExistingLogs->bindParam(":session_id", $session_id, PDO::PARAM_INT);
    $stmtExistingLogs->bindParam(":day_number", $day_number, PDO::PARAM_INT);
    $stmtExistingLogs->execute();

    while ($row = $stmtExistingLogs->fetch(PDO::FETCH_ASSOC)) {
        $exerciseLogs[$row["exercise_number"]][$row["set_number"]] = $row;
    }
}
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
    <title>jacklouispt - Log Workout</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles-global.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php setFavicon(); ?>

    <style>

    .two-column {
      display: block;
      height: auto;
      min-height: initial !important; /* Override the min-height property */
    }

    .flex-container {
      display: flex;
    }

    .flex-container div {
      margin: 5px;
    }

    .exercise-container-inner {
        display: flex;
        align-items: center; /* Vertically center align items within this container */
    }

    .exercise-container.row {
       background-color: #F1F1F1;
       margin-bottom: 10px;
       border-radius: 10px;
    }


    </style>

</head>




<body>

  <!-- Top bar -->
  <?php displayTopBar(); ?>

  <div class="section">
    <div class="box">
        <div class="page-heading">
            <h1><?php echo $programmeDays . " Day " . ucfirst($fitness_level) . " " . $programmeName; ?> Programme</h1>
        </div>

        <h2>Edit Workout Log: Day <?php echo $day_number ?></h2>
        <p>___</p>
        <p>Enter the average amount of weight you have used for each weight exercise along with the number of reps for each set. For cardio exercises, you can leave an optional note to document how you feel.</p>

        <form action="process-exercise-log.php" method="post">
            <?php
            $exercises = getExercisesForDay($programmeID, $day_number, $pdo);
            $exerciseNumber = 0;
            foreach ($exercises as $exercise) {
                    $exerciseNumber = $exerciseNumber + 1;

                    echo "<div class='exercise-container row'>";
                    echo "<div class='exercise-container-inner'>";

                    echo "<div class='exercise-details two-column'>";
                    echo "<h3>{$exercise['name']}</h3>";

                    if ($exercise['type'] === 'weight') {
                        $sets = isset($exercise['sets']) ? intval($exercise['sets']) : 1; // Default to 1 set if 'sets' is not specified
                        for ($i = 1; $i <= $sets; $i++) {
                            echo "<div class='flex-container'>";
                            echo "<h4>Set {$i}:</h4>";
                            echo "<div class='exercise-field'>";
                            echo "<label>Weight*:</label><br>";
                            // Check if there's a corresponding exercise log entry
                            $weightValue = isset($exerciseLogs[$exerciseNumber][$i]['weight']) ? $exerciseLogs[$exerciseNumber][$i]['weight'] : '';
                            echo "<input type='number' name='weight[{$exerciseNumber}][{$i}]' required min='1' max='250' value='{$weightValue}'>";
                            echo "</div>";
                            echo "<div class='exercise-field'>";
                            echo "<label>Reps*:</label><br>";
                            // Check if there's a corresponding exercise log entry
                            $repsValue = isset($exerciseLogs[$exerciseNumber][$i]['reps']) ? $exerciseLogs[$exerciseNumber][$i]['reps'] : '';
                            echo "<input type='number' name='reps[{$exerciseNumber}][{$i}]' required min='1' max='20' value='{$repsValue}'>";
                            echo "</div><br>";
                            echo "</div>";
                        }
                    } elseif ($exercise['type'] === 'cardio') {
                        echo "<div class='exercise-field'>";
                        echo "<label>Note:</label><br>";
                        // Check if there's a corresponding exercise log entry
                        $noteValue = isset($exerciseLogs[$exerciseNumber][1]['note']) ? $exerciseLogs[$exerciseNumber][1]['note'] : '';
                        echo "<textarea name='note[{$exerciseNumber}]' style='width: 100%;'>{$noteValue}</textarea>";
                        echo "</div>";
                    }

              echo "</div>"; // Close exercise-details div

              echo "<div class='exercise-gif two-column'>
                  <img src='{$exercise['gif']}' alt='{$exercise['name']}' style='width: 100%; height: 160px; object-fit: cover; object-position: 50% 0%;'>
                </div>";

              echo "</div>"; // Close exercise-container-inner div
              echo "</div>"; // Close exercise-container div
            }
            ?>
            <input type="hidden" name="session_id" value="<?= $session_id ?>">
            <input type="hidden" name="day_number" value="<?= $day_number ?>">
            <button type="submit" style="background-color: #E10101; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Update Log</button>
        </form>
    </div>
</div>










<?php displayFooter(); ?>








</body>
</html>

<?php
session_start();


include "functions.php";
$pdo = pdo_connect_mysql();

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["session_id"])) {
    header("Location: dashboard.php");
    exit();
}

$username = $_SESSION["name"];
$session_id = $_GET["session_id"];
$account_id = getAccountIDFromUsername($pdo, $username);


// Extract information from ProgrammeSession mysql_list_tables
$queryProgrammeSession = "SELECT programme_id, start_date, end_date FROM ProgrammeSession WHERE session_id = :session_id";
$stmtProgrammeSession = $pdo->prepare($queryProgrammeSession);
$stmtProgrammeSession->bindParam(":session_id", $session_id, PDO::PARAM_INT);
$stmtProgrammeSession->execute();
$programmeSessionDetail = $stmtProgrammeSession->fetch(PDO::FETCH_ASSOC);

$programme_id = $programmeSessionDetail['programme_id'];
$start_date = $programmeSessionDetail['start_date'];
$end_date = $programmeSessionDetail['end_date'];



// Check if the user has permission to view this programme
$queryCheckPermission = "
    SELECT COUNT(*) AS permission_count
    FROM ProgrammeSession
    WHERE account_id = :account_id AND programme_id = :programme_id AND completed = 1";
$stmtCheckPermission = $pdo->prepare($queryCheckPermission);
$stmtCheckPermission->bindParam(":account_id", $account_id, PDO::PARAM_INT);
$stmtCheckPermission->bindParam(":programme_id", $programme_id, PDO::PARAM_INT);
$stmtCheckPermission->execute();
$permissionCount = $stmtCheckPermission->fetchColumn();

if ($permissionCount === 0) {
    header("Location: dashboard.php");
    exit();
}

// Extract information from Programme table
$queryProgramme = "SELECT name, days, category, fitness_level FROM Programme WHERE programme_id = :programme_id";
$stmtProgramme = $pdo->prepare($queryProgramme);
$stmtProgramme->bindParam(":programme_id", $programme_id, PDO::PARAM_INT);
$stmtProgramme->execute();
$programmeDetail = $stmtProgramme->fetch(PDO::FETCH_ASSOC);

$programme_name = $programmeDetail['name'];
$programme_days = $programmeDetail['days'];
$programme_category = $programmeDetail['category'];
$programme_difficulty = $programmeDetail['fitness_level'];




?>
<!DOCTYPE html>
<html>
<head>
    <title>jacklouispt - Programme Review</title>
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
        <h1>Archived Programme Overview</h1>
    </div>

    <div class="section">
    <div class="box">
      <h2>Programme Summary</h2>
      <p>___</p>
      <?php
      echo "<p>Name: " . $programme_name . "</p>";
      echo "<p>Days: " . $programme_days . "</p>";
      echo "<p>Level: " . ucfirst($programme_difficulty) . "</p>";
      echo "<p>Category: " . $programme_category . "</p>";

      echo "<p>You started the programme on " . $start_date . " and marked it as complete on " . $end_date . ".</p>";

      for ($i = 1; $i <= $programme_days; $i++) {
    $queryLog = "SELECT Exercise.name, ExerciseLog.reps, ExerciseLog.weight
                 FROM Exercise, ExerciseLog, ProgrammeSession
                 WHERE Exercise.day_number = ExerciseLog.day_number
                 AND Exercise.exercise_number = ExerciseLog.exercise_number
                 AND ExerciseLog.session_id = ProgrammeSession.session_id
                 AND ProgrammeSession.session_id = :session_id
                 AND Exercise.type = 'weight'
                 AND Exercise.programme_id = :programme_id
                 AND ExerciseLog.day_number = :i";
    $stmtLog = $pdo->prepare($queryLog);
    $stmtLog->bindParam(":session_id", $session_id, PDO::PARAM_INT);
    $stmtLog->bindParam(":programme_id", $programme_id, PDO::PARAM_INT);
    $stmtLog->bindParam(":i", $i, PDO::PARAM_INT);
    $stmtLog->execute(); // Execute the query
    $exercises_log = $stmtLog->fetchAll(PDO::FETCH_ASSOC); // Fetch the data

    echo "<h3>Day " . $i . " Log</h3>";
    echo "<table class='exercise-table'>
            <tr>
              <th>Exercise</th>
              <th>Reps</th>
              <th>Weight</th>
            </tr>";

    foreach ($exercises_log as $exercise_log) {
        echo "<tr>
                  <td>{$exercise_log["name"]}</td>
                  <td>{$exercise_log["reps"]}</td>
                  <td>{$exercise_log["weight"]}</td>
              </tr>";
    }

    echo "</table>";

    $queryLog = "SELECT Exercise.name, ExerciseLog.note
             FROM Exercise, ExerciseLog, ProgrammeSession
             WHERE Exercise.day_number = ExerciseLog.day_number
             AND Exercise.exercise_number = ExerciseLog.exercise_number
             AND ExerciseLog.session_id = ProgrammeSession.session_id
             AND ProgrammeSession.session_id = :session_id
             AND Exercise.type = 'cardio'
             AND Exercise.programme_id = :programme_id
             AND ExerciseLog.day_number = :i";
$stmtLog = $pdo->prepare($queryLog);
$stmtLog->bindParam(":session_id", $session_id, PDO::PARAM_INT);
$stmtLog->bindParam(":programme_id", $programme_id, PDO::PARAM_INT);
$stmtLog->bindParam(":i", $i, PDO::PARAM_INT);
$stmtLog->execute(); // Execute the query
$exercises_log = $stmtLog->fetchAll(PDO::FETCH_ASSOC); // Fetch the data

if (!empty($exercises_log)) {
    echo "<table class='exercise-table'>
            <tr>
              <th>Exercise</th>
              <th>Note</th>
            </tr>";

    foreach ($exercises_log as $exercise_log) {
        echo "<tr>
                <td>{$exercise_log["name"]}</td>
                <td>{$exercise_log["note"]}</td>
              </tr>";
    }

    echo "</table>";
}


}
       ?>
    </div>
  </div>




    <?php displayFooter(); ?>

</body>
</html>

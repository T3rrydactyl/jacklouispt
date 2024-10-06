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
$session_id = $_GET["session_id"];


// Confirm the session is incomplete and it belongs to the logged in user.
$queryValidSession =
    "SELECT ProgrammeSession.programme_id, session_id, Account.account_id, username, name, days, category FROM Programme, ProgrammeSession, Account WHERE Programme.programme_id = ProgrammeSession.programme_id AND ProgrammeSession.session_id = :session_id AND Account.username = :username AND completed = 0";
$stmtValidSession = $pdo->prepare($queryValidSession);
$stmtValidSession->bindParam(":username", $username, PDO::PARAM_STR);
$stmtValidSession->bindParam(":session_id", $session_id, PDO::PARAM_INT);
$stmtValidSession->execute();
$sessionDetail = $stmtValidSession->fetch(PDO::FETCH_ASSOC);

$programmeName = $sessionDetail["name"];
$programmeID = $sessionDetail["programme_id"];
$programmeDays = $sessionDetail["days"];
$category = $sessionDetail["category"];
$fitness_level = getDifficultyFromProgrammeID($pdo, $programmeID);


// Generate tab buttons based on $programmeDays
$tabButtons = "";
for ($i = 1; $i <= $programmeDays; $i++) {
    $tabButtons .= "<button class='tab-button' onclick='showTab($i)'>Day $i</button>";
}

if ($stmtValidSession->rowCount() == 0) {
    // No matching sessions are found for the logged in user
    echo "No session details found.";
}
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
    <title>jacklouispt - Programme Overview</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles-global.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php setFavicon(); ?>

    <style>

      .tab-container {
  width: 100%;
  max-width: 90%;
  margin: 0 auto;
  margin-top: 5px;
  margin-bottom: 5px;
}

.two-column {
    display: block;
  }

.tab-buttons {
  display: flex;
  flex-wrap: wrap; /* Allow buttons to wrap to the next line */
  justify-content: flex-start; /* Align buttons to the left */
  gap: 10px;
}

.tab-button {
  width: 100px; /* Fixed width for each button */
  padding: 10px;
  border: none;
  background-color: #f0f0f0;
  cursor: pointer;
  text-align: center;
  font-weight: bold; /* Add this line to make the text bold */
}

.tab-panel {
  display: none;
  padding: 20px;
  border: 1px solid #ccc;
}

.tab-panel.active {
  display: block;
}




@media (max-width: 480px) {
  .tab-container {
max-width: 95%;

}

}

    </style>

</head>




<body>

  <!-- Top bar -->
  <?php displayTopBar(); ?>

  <div class="section">
  <div class="page-heading">
    <h1> <?php echo $programmeDays . " Day " . ucfirst($fitness_level) . " " . $programmeName; ?> Programme</h1>
  </div>
  <h2>Select a Day</h2>
  <p>___</p>
  <p>Discover each day's exercises by choosing the relevant button below. Select an exercise to view its explanation video. You can also log your workout for the day so that you can look back on it later.</p>
</div>


    <div class="tab-container">
  <div class="tab-buttons">
    <?php echo $tabButtons; ?>
  </div>
  <div class="tab-content">
  <?php
  $allDaysCompleted = true; // Assume all days are completed initially

  for ($i = 1; $i <= $programmeDays; $i++) {
      $exercises = getExercisesForDay($programmeID, $i, $pdo);

      // Add 'active' class to the first day's tab-panel
      $activeClass = ($i === 1) ? 'active' : '';

      echo "<div class='tab-panel $activeClass' id='tab$i'>
                  <h3>Day $i Overview</h3>
                  <table class='exercise-table'>
                      <tr>
                          <th>Exercise (Video)</th>
                          <th>Sets</th>
                          <th>Rep Range</th>
                      </tr>";

      foreach ($exercises as $exercise) {
          $exerciseLink = "exercise-video.php?programme_id={$exercise["programme_id"]}&session_id={$session_id}&day_number={$exercise["day_number"]}&exercise_number={$exercise["exercise_number"]}";
          echo "<tr>
                    <td><a href='$exerciseLink'>{$exercise["name"]} ></a></td>
                      <td>{$exercise["sets"]}</td>
                      <td>{$exercise["rep_range"]}</td>
                  </tr>";
      }

      echo "</table>";

      // Check if the form has been submitted already
      $querySessionDayLogCount = "SELECT count(*) FROM ExerciseLog WHERE session_id = :session_id AND day_number = :day_number";
      $stmtSessionDayLogCount = $pdo->prepare($querySessionDayLogCount);
      $stmtSessionDayLogCount->bindParam(":session_id", $session_id, PDO::PARAM_INT);
      $stmtSessionDayLogCount->bindParam(":day_number", $i, PDO::PARAM_INT);
      $stmtSessionDayLogCount->execute();
      $sessionDayLogCount = $stmtSessionDayLogCount->fetchColumn();

      if ($sessionDayLogCount == 0){
        $allDaysCompleted = false;
        echo "
                    <h3>Log Your Workout</h3>
                    <p>Keep a record of what you have done today.</p>
                    <a href='log-workout.php?session_id=" . $session_id . "&day_number=" . $i . "'>Open Form ></a>
                  ";
      } else {
        echo "
                    <h3>Log Your Workout</h3>
                    <p>You have already logged your workout for today.</p>
                    <a href='edit-workout.php?session_id=" . $session_id . "&day_number=" . $i . "'>Edit Submission ></a>
                  ";
      }


      echo "</div>"; // Close the tab-panel
  } ?>

</div>


</div>

<div class="section">
<?php // Display the completion message if all days are completed
if ($allDaysCompleted) {
  echo "<h2>Programme Completion</h2>";
  echo "<p>___</p>";
  echo "<p>Congratulations! We have received your workout log submissions for all days in this programme. You're now prepared to finalise this achievement by archiving it. Feel free to revisit your workout logs whenever you like by accessing the programme in the 'Archived Programmes' section of your User Dashboard. Well done!</p>";
  echo "<a href='archive-programme.php?session_id=" . $session_id . "'>Archive Programme ></a>";
} else {
echo "<h2>Programme Completion</h2>";
echo "<p>___</p>";
echo "<p>In order to mark this programme as complete, you must submit a workout log for each day. If you are having second thoughts about starting this programme, simply remove it in your User Dashboard and pick another.</p>";
}?>
</div>


<div class="section" style="position: relative; min-height: 800px;">

    <div class="two-column">
      <div class='banner'>
                <img src='images/programme-overview/meal-plans.jpg'>
                <div class='centered'>
                  <h2>View Meal Plans</h2>
                  <?php echo "<p>Here are the meals we suggest for " . $category . ".</p>" ?>
                  <a href=''>Released weekly. First one next week.</a>
                </div>
      </div>
    </div>

    <div class="two-column">
      <div class='banner'>
                <img src='images/programme-overview/calorie-calculator.jpg'>
                <div class='centered'>
                  <h2>Calorie Calculator</h2>
                  <p>Want to know how many calories you should be eating?.</p>
                  <a href='calorie-calculator.php'>Open Calculator ></a>
                </div>
      </div>
    </div>

</div>







<?php displayFooter(); ?>






  <script>
  function showTab(tabNumber) {
    const tabPanels = document.querySelectorAll('.tab-panel');
    tabPanels.forEach(panel => panel.classList.remove('active'));

    const selectedTab = document.getElementById(`tab${tabNumber}`);
    selectedTab.classList.add('active');
  }

  </script>

</body>
</html>

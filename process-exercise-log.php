<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "functions.php";
$pdo = pdo_connect_mysql();

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $session_id = $_POST["session_id"];
    $day_number = $_POST["day_number"];

    // Fetch the programmeID based on session_id
    $queryProgrammeID = "SELECT programme_id FROM ProgrammeSession WHERE session_id = :session_id";
    $stmtProgrammeID = $pdo->prepare($queryProgrammeID);
    $stmtProgrammeID->bindParam(":session_id", $session_id, PDO::PARAM_INT);
    $stmtProgrammeID->execute();
    $programmeID = $stmtProgrammeID->fetchColumn();

    $exercises = getExercisesForDay($programmeID, $day_number, $pdo);

    foreach ($exercises as $exerciseNumber => $exercise) {
        // Assign exercise number
        $exerciseNumberForQuery = $exerciseNumber + 1;

        $exerciseType = $exercise['type'];

        if ($exerciseType === 'weight') {
            $sets = isset($exercise['sets']) ? intval($exercise['sets']) : 1; // Default to 1 set if 'sets' is not specified
            $note = null; // Initialize note for weight exercises
        } else if ($exerciseType === 'cardio') {
            $sets = 1; // Cardio exercises have 1 set
        }

        // Process each set separately
        for ($setNumber = 1; $setNumber <= $sets; $setNumber++) {
            // Check if a log for this exercise and set already exists
            $queryCheckLog = "SELECT * FROM ExerciseLog
                WHERE session_id = :session_id
                AND day_number = :day_number
                AND exercise_number = :exercise_number
                AND set_number = :set_number";

            $stmtCheckLog = $pdo->prepare($queryCheckLog);
            $stmtCheckLog->bindParam(":session_id", $session_id, PDO::PARAM_INT);
            $stmtCheckLog->bindParam(":day_number", $day_number, PDO::PARAM_INT);
            $stmtCheckLog->bindParam(":exercise_number", $exerciseNumberForQuery, PDO::PARAM_INT);
            $stmtCheckLog->bindParam(":set_number", $setNumber, PDO::PARAM_INT);
            $stmtCheckLog->execute();


            if ($stmtCheckLog->rowCount() > 0) {

                // Determine if weight and reps are provided (only for weight exercises)
                if ($exerciseType === 'weight') {
                    $weight = isset($_POST['weight'][$exerciseNumber + 1][$setNumber]) ? intval($_POST['weight'][$exerciseNumber + 1][$setNumber]) : null;
                    $reps = isset($_POST['reps'][$exerciseNumber + 1][$setNumber]) ? intval($_POST['reps'][$exerciseNumber + 1][$setNumber]) : null;
                } else {
                    $weight = null;
                    $reps = null;
                    $note = isset($_POST['note'][$exerciseNumber + 1]) ? $_POST['note'][$exerciseNumber + 1] : null;
                }

                // Update existing log
                $queryUpdate = "UPDATE ExerciseLog
                    SET weight = :weight, reps = :reps, note = :note
                    WHERE session_id = :session_id
                    AND day_number = :day_number
                    AND exercise_number = :exercise_number
                    AND set_number = :set_number";

                $stmtUpdate = $pdo->prepare($queryUpdate);
                $stmtUpdate->bindParam(":weight", $weight, PDO::PARAM_INT);
                $stmtUpdate->bindParam(":reps", $reps, PDO::PARAM_INT);
                $stmtUpdate->bindParam(":note", $note, PDO::PARAM_STR);
                $stmtUpdate->bindParam(":session_id", $session_id, PDO::PARAM_INT);
                $stmtUpdate->bindParam(":day_number", $day_number, PDO::PARAM_INT);

                $stmtUpdate->bindParam(":exercise_number", $exerciseNumberForQuery, PDO::PARAM_INT);
                $stmtUpdate->bindParam(":set_number", $setNumber, PDO::PARAM_INT);
                $stmtUpdate->execute();
            } else {

                if ($exerciseType === 'weight') {
                    $weight = isset($_POST['weight'][$exerciseNumber][$setNumber]) ? intval($_POST['weight'][$exerciseNumber][$setNumber]) : null;
                    $reps = isset($_POST['reps'][$exerciseNumber][$setNumber]) ? intval($_POST['reps'][$exerciseNumber][$setNumber]) : null;
                  } else {
                    $weight = null;
                    $reps = null;
                    $note = isset($_POST['note'][$exerciseNumber]) ? $_POST['note'][$exerciseNumber] : null;
                  }
                // Insert new log
                $queryInsert = "INSERT INTO ExerciseLog (session_id, day_number, exercise_number, set_number, weight, reps, note)
                    VALUES (:session_id, :day_number, :exercise_number, :set_number, :weight, :reps, :note)";

                $stmtInsert = $pdo->prepare($queryInsert);
                $stmtInsert->bindParam(":session_id", $session_id, PDO::PARAM_INT);
                $stmtInsert->bindParam(":day_number", $day_number, PDO::PARAM_INT);
                $stmtInsert->bindParam(":exercise_number", $exerciseNumberForQuery, PDO::PARAM_INT);
                $stmtInsert->bindParam(":set_number", $setNumber, PDO::PARAM_INT);
                $stmtInsert->bindParam(":weight", $weight, PDO::PARAM_INT);
                $stmtInsert->bindParam(":reps", $reps, PDO::PARAM_INT);
                $stmtInsert->bindParam(":note", $note, PDO::PARAM_STR);
                $stmtInsert->execute();
            }
        }
    }

    // Redirect back to programme-overview.php
    header("Location: programme-overview.php?session_id={$session_id}");
    exit();
}
?>

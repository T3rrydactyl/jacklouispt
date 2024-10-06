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

$session_id = $_GET["session_id"];

// Check if the user has permission to archive this session
$username = $_SESSION["name"];
$queryValidSession = "SELECT ProgrammeSession.session_id, ProgrammeSession.programme_id, Account.username FROM ProgrammeSession, Account WHERE ProgrammeSession.session_id = :session_id AND ProgrammeSession.account_id = Account.account_id AND Account.username = :username";
$stmtValidSession = $pdo->prepare($queryValidSession);
$stmtValidSession->bindParam(":username", $username, PDO::PARAM_STR);
$stmtValidSession->bindParam(":session_id", $session_id, PDO::PARAM_INT);
$stmtValidSession->execute();

if ($stmtValidSession->rowCount() === 0) {
    // No matching sessions are found for the logged in user
    echo "You don't have permission to archive this programme.";
    exit();
}

$sessionDetail = $stmtValidSession->fetch(PDO::FETCH_ASSOC);
$programmeID = $sessionDetail["programme_id"];
$programmeDays = (int) $pdo->query("SELECT days FROM Programme WHERE programme_id = $programmeID")->fetchColumn();

$queryLogCount = "SELECT count(*) FROM ExerciseLog WHERE session_id = :session_id AND day_number <= :programme_days GROUP BY day_number";
$stmtLogCount = $pdo->prepare($queryLogCount);
$stmtLogCount->bindParam(":session_id", $session_id, PDO::PARAM_INT);
$stmtLogCount->bindParam(":programme_days", $programmeDays, PDO::PARAM_INT);
$stmtLogCount->execute();
$logCounts = $stmtLogCount->fetchAll(PDO::FETCH_COLUMN);


if (count($logCounts) === $programmeDays) {
    // Update the ProgrammeSession table
    $queryUpdateSession = "UPDATE ProgrammeSession SET completed = 1, end_date = :end_date WHERE session_id = :session_id";
    $stmtUpdateSession = $pdo->prepare($queryUpdateSession);
    $stmtUpdateSession->bindParam(":end_date", date("Y-m-d"), PDO::PARAM_STR);
    $stmtUpdateSession->bindParam(":session_id", $session_id, PDO::PARAM_INT);
    $stmtUpdateSession->execute();

    // Redirect to dashboard
    header("Location: dashboard.php");
    exit();
} else {
    // Not all days have logs submitted
    echo "You need to submit workout logs for all days to archive the programme.";
}
?>

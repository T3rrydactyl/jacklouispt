<?php
session_start();
include "functions.php";
$pdo = pdo_connect_mysql();

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["name"];
$session_id = $_GET["session_id"];

$queryValidSession = "SELECT ProgrammeSession.session_id, ProgrammeSession.programme_id, Account.username FROM ProgrammeSession, Account WHERE ProgrammeSession.session_id = :session_id AND ProgrammeSession.account_id = Account.account_id AND Account.username = :username";
$stmtValidSession = $pdo->prepare($queryValidSession);
$stmtValidSession->bindParam(":username", $username, PDO::PARAM_STR);
$stmtValidSession->bindParam(":session_id", $session_id, PDO::PARAM_INT);
$stmtValidSession->execute();

if ($stmtValidSession->rowCount() === 0) {
    echo "You don't have permission to remove this programme.";
    exit();
}

// Delete records from ExerciseLog for this session
$queryDeleteLogs = "DELETE FROM ExerciseLog WHERE session_id = :session_id";
$stmtDeleteLogs = $pdo->prepare($queryDeleteLogs);
$stmtDeleteLogs->bindParam(":session_id", $session_id, PDO::PARAM_INT);
$stmtDeleteLogs->execute();

// Delete records from ProgrammeSession for this session
$queryDeleteSession = "DELETE FROM ProgrammeSession WHERE session_id = :session_id";
$stmtDeleteSession = $pdo->prepare($queryDeleteSession);
$stmtDeleteSession->bindParam(":session_id", $session_id, PDO::PARAM_INT);
$stmtDeleteSession->execute();

header("Location: dashboard.php");
exit();
?>

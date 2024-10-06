<?php

session_start();
include "functions.php";
$pdo = pdo_connect_mysql();

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["name"];
$category = $_GET["category"];
$today = date('Y-m-d');


$programmeID = $_GET["programme_id"];
$accountID = getAccountIDFromUsername($pdo, $username);

if ($category == "Fat Loss") {
    $license = 2;
    $fatLossLicenseCount = getLicenseCount($pdo, $username, $license);
    if ($fatLossLicenseCount == 0) {
        echo "You do not have access to this programme.";
    } else {
        $queryNewFatLoss =
            "INSERT INTO ProgrammeSession (`account_id`, `programme_id`, `completed`, `start_date`) VALUES (:account_id, :programme_id, '0', :start_date)";
        $stmtNewFatLoss = $pdo->prepare($queryNewFatLoss);
        $stmtNewFatLoss->bindParam(":account_id", $accountID, PDO::PARAM_STR);
        $stmtNewFatLoss->bindParam(":programme_id", $programmeID, PDO::PARAM_INT);
        $stmtNewFatLoss->bindParam(':start_date', $today, PDO::PARAM_STR);
        $stmtNewFatLoss->execute();

        header("Location: dashboard.php");
        exit();
    }
} elseif ($category == "Muscle Growth") {
    $license = 1;
    $muscleGrowthLicenseCount = getLicenseCount($pdo, $username, $license);
    if ($muscleGrowthLicenseCount == 0) {
        echo "You do not have access to this programme.";
    } else {
        $queryNewMuscleGrowth =
            "INSERT INTO ProgrammeSession (`account_id`, `programme_id`, `completed`, `start_date`) VALUES (:account_id, :programme_id, '0', :start_date)";
        $stmtNewMuscleGrowth = $pdo->prepare($queryNewMuscleGrowth);
        $stmtNewMuscleGrowth->bindParam(":account_id", $accountID, PDO::PARAM_STR);
        $stmtNewMuscleGrowth->bindParam(":programme_id", $programmeID, PDO::PARAM_INT);
        $stmtNewMuscleGrowth->bindParam(':start_date', $today, PDO::PARAM_STR);
        $stmtNewMuscleGrowth->execute();

        header("Location: dashboard.php");
        exit();
    }
}
?>

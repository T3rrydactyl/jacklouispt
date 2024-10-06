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

// Assuming you have the pdo_connect_mysql() function defined

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the selected fitness level from the form
    $fitnessLevel = $_POST["fitnessLevel"];

    try {
        // Prepare and execute the query using prepared statements
        $query = "UPDATE Account SET fitness_level = :fitnessLevel WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":fitnessLevel", $fitnessLevel, PDO::PARAM_STR);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();

        // Redirect to dashboard.php upon successful query
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        echo "Error updating fitness level: " . $e->getMessage();
    }

    // Close the database connection
    $pdo = null;
}
?>

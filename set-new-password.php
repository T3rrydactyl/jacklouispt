<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// Include functions and connect to the database using PDO MySQL
include "functions.php";

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'jackloui_dbUSER';
$DATABASE_PASS = 'murkynight77';
$DATABASE_NAME = 'jackloui_phplogin';

try {
    $pdo = new PDO("mysql:host=$DATABASE_HOST;dbname=$DATABASE_NAME", $DATABASE_USER, $DATABASE_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_SESSION['password-reset-email'], $_SESSION['password_reset_code'], $_POST['password1'], $_POST['password2'])) {
        if ($_POST['password1'] != $_POST['password2']) {
            echo "Passwords do not match. Please enter your new password again.";
            exit();
        } else if (strlen($_POST['password1']) > 20 || strlen($_POST['password1']) < 6) {
            exit('Password must be between 6 and 20 characters long!');
        } else {
            $email = $_SESSION['password-reset-email'];
            $resetCode = $_SESSION['password_reset_code'];

            $stmt = $pdo->prepare('UPDATE Account SET password_reset_code = NULL WHERE email = :email AND password_reset_code = :resetCode');
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':resetCode', $resetCode);
            $stmt->execute();

            $hashedPassword = password_hash($_POST['password1'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE Account SET password = :hashedPassword WHERE email = :email');
            $stmt->bindParam(':hashedPassword', $hashedPassword);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            pageWithOneMsg('Password Reset', 'Your password has been successfully updated. Head to the <a href="login.php">login</a> page to sign in.');
            exit();
        }
        header("Location: page-not-found.php");
    }
} catch (PDOException $e) {
    echo "An error occurred: " . $e->getMessage();
}
?>

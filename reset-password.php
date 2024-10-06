<?php
session_start();
include 'functions.php';

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'jackloui_dbUSER';
$DATABASE_PASS = 'murkynight77';
$DATABASE_NAME = 'jackloui_phplogin';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if (!isset($_POST['username'], $_POST['email'])) {
    exit('Please fill out both your username and email.');
}

if (empty($_POST['username']) || empty($_POST['email'])) {
    exit('Please fill out both your username and email.');
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    exit('Email is not valid.');
}

$query = "SELECT account_id, username, email, password FROM Account WHERE username = ? AND email = ?";
$stmt = mysqli_prepare($con, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $_POST['username'], $_POST['email']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $query = "UPDATE Account SET password_reset_code = ? WHERE username = ? AND email = ?";
        $uniqid = uniqid();
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $uniqid, $_POST['username'], $_POST['email']);
            mysqli_stmt_execute($stmt);

            $from = 'no-reply@jacklouispt.com';
            $subject = 'Reset Your Account Password';
            $headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
            $activate_link = 'https://jacklouispt.com/enter-new-password.php?email=' . $_POST['email'] . '&code=' . $uniqid;
            $message = '<p>Please click the following link to reset your account password: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';
            $htmlEmail = createHtmlEmail($subject, $message);
            mail($_POST['email'], $subject, $htmlEmail, $headers);
            pageWithOneMsg('Please Check Your Email', 'We have sent you a password reset link to your email. Please note that in some rare circumstances, it could take up to 5 minutes to receive the email.');
        } else {
            echo 'Could not prepare statement for UPDATE.';
        }
    } else {
        pageWithOneMsg('Invalid Request', 'We could not match the entered username to that email. Please contact us if you have lost your username. Click <a href="password-reset-form.php"> here </a> to return back to the password reset form.');
    }

    mysqli_stmt_close($stmt);
} else {
    echo 'Could not prepare statement for SELECT.';
}

mysqli_close($con);
?>

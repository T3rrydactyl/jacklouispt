<?php
session_start();
include 'functions.php';
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'jacklou1_dbUSER';
$DATABASE_PASS = 'murkynight77';
$DATABASE_NAME = 'jacklou1_phplogin';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    // If there is an error with the connection, stop the script and display the error.
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	// Could not get the data that should have been sent.
	exit('Please complete the registration form.');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	// One or more values are empty.
	exit('Please complete the registration form.');
}

//Validates email
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit('Email is not valid!');
}

//Ensures username is letters and numbers
if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    exit('Username is not valid!');
}

//Ensures password is between 6 and 20 characters
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 6) {
	exit('Password must be between 6 and 20 characters long!');
}

// We need to check if the account with that username exists.
if ($stmt = $con->prepare('SELECT account_id, password FROM Account WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Username already exists
		echo 'This username is already in use. Please enter a different username.';
		$stmt->close();
		$con->close();
		exit();
	} else {
		$stmt->close();
	}
} else {
	// Something is wrong with the SQL statement, so you must check to make sure your accounts table exists with all 3 fields.
	echo 'Could not prepare statement!';
	$con->close();
	exit();
}

// We also need to check if the account with that email exists.
if ($stmt = $con->prepare('SELECT account_id, password FROM Account WHERE email = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc)
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Email already exists
		echo 'An account with this email already exists. Please reset your password or contact us if you have forgotten your username.';
		$stmt->close();
		$con->close();
		exit();
	} else {
		$stmt->close();
	}
} else {
	// Something is wrong with the SQL statement, so you must check to make sure your accounts table exists with the 'email' field.
	echo 'Could not prepare statement!';
	$con->close();
	exit();
}

// Inserts new account
if ($stmt = $con->prepare('INSERT INTO Account (username, password, email, activation_code) VALUES (?, ?, ?, ?)')) {
	// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $uniqid = uniqid();
  $stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $uniqid);

	$stmt->execute();
  $from    = 'no-reply@jacklouispt.com';
  $subject = 'Activate Your New Account';
  $headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
  // Update the activation variable below
  $activate_link = 'https://jacklouispt.com/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;
  $message = '<p>Please click the following link to activate your new account: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';
  $htmlEmail = createHtmlEmail($subject, $message);
  mail($_POST['email'], $subject, $htmlEmail, $headers);
  pageWithOneMsg('Account Activation', 'An Account Activation link has been sent to your email. Please click on it to activate your account. In some rare circumstances, it may take up to 5 minutes to receive the email.');
} else {
	// Something is wrong with the SQL statement, so you must check to make sure your accounts table exists with all 3 fields.
	echo 'Could not prepare statement!';
}

$con->close();
?>

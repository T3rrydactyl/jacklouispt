<?php
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
// First we check if the email and code exists...
if (isset($_GET['email'], $_GET['code'])) {
	if ($stmt = $con->prepare('SELECT * FROM Account WHERE email = ? AND activation_code = ?')) {
		$stmt->bind_param('ss', $_GET['email'], $_GET['code']);
		$stmt->execute();
		// Store the result so we can check if the account exists in the database.
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			// Account exists with the requested email and code.
			if ($stmt = $con->prepare('UPDATE Account SET activation_code = ? WHERE email = ? AND activation_code = ?')) {
				// Set the new activation code to 'activated', this is how we can check if the user has activated their account.
				$newcode = 'activated';
				$stmt->bind_param('sss', $newcode, $_GET['email'], $_GET['code']);
				$stmt->execute();
				pageWithOneMsg('Your Account is Activated', 'You have successfully activated your new account. Head to the <a href="login.php">login</a> page to sign in.');
			}
		} else {
			pageWithOneMsg('Activation Error', 'You have either activated this account already or have clicked on a broken link.');
		}
	}
}
?>

<?php
session_start();

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

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (!isset($_POST['username'], $_POST['password'])) {
    // Could not get the data that should have been sent.
    exit('Please fill both the username and password fields!');
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $con->prepare('SELECT account_id, password FROM Account WHERE username = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();

    // Check if the query executed successfully
    if ($stmt->errno) {
        exit('An error occurred while executing the query.');
    }

    // Check if the account exists
    if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $password);
    $stmt->fetch();
    if (password_verify($_POST['password'], $password)) {
        // Verification success! User has logged-in!
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $_POST['username'];
        $_SESSION['id'] = $id;
        header('Location: dashboard.php');
    } else {
        // Incorrect password
        $_SESSION['login_error'] = 'Incorrect username and/or password.';
        header('Location: login.php');
    }
} else {
    // Incorrect username
    $_SESSION['login_error'] = 'Incorrect username and/or password.';
    header('Location: login.php');
}

    // Close the prepared statement after working with the result set
    $stmt->close();
} else {
    // Error with the prepared statement
    exit('An error occurred while preparing the SQL statement.');
}
?>

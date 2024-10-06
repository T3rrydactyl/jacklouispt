<?php
function setFavicon() {
  echo '<link rel="icon" href="images/favicon/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">';
}

function displayTopBar() {
  echo '<div class="top-bar">
      <div class="logo">
          <a href="/index.php">
              <img src="/images/logo.png" alt="JackLouisPT Logo">
          </a>
      </div>';

  if (!isset($_SESSION['loggedin'])) {
      // User is not logged in, display default menu for guests
      displayLoggedOutNav();
  } else {
      // User is logged in, display logged-in menu
      displayLoggedInNav();
  }

  echo '</div>';
}

function displayLoggedOutNav() {
    echo '<div class="user-menu">
        <span class="welcome-message">Welcome, please</span>
        <a href="/login.php" class="login-header">login</a>
        <span class="welcome-message">or</span>
        <a href="/registration-form.php" class="register-header">register</a>
        <span class="shopping-cart">';

    // Call the display_cart() function here and echo its output
    echo display_cart();

    echo '</span>
    </div>';
}

function displayLoggedInNav() {
    echo '<div class="logged-in-menu">
        <div class="user-menu">
            <div class="welcome-message">Welcome, ' . $_SESSION['name'] . '<br>
            <a href="/dashboard.php">⏱ Dashboard</a> <a href="/profile.php">☻ My Profile</a> <a href="/log-out.php">𓉞 Log Out</a>
            </div>
            <span class="shopping-cart">';

    // Call the display_cart() function here and echo its output
    echo display_cart();

    echo '</span>
        </div>
    </div>';
}


function displayFooter() {
  echo '<footer>
    <div class="footer">
      <div class="footer-text">
        <h3>jacklouispt</h3>
        <br>
        <a href="/terms-of-service.php">Terms of Service</a> <a href="/privacy-policy.php">Privacy Policy</a> <a href="password-reset-form.php">Reset Password</a>
        <p>___</p>
        <p>Unlock Your Potential, Embrace Your Fitness Journey</p>
        <br>
        <p>jacklouispt sets a new standard in the fitness industry, offering unparalleled expertise that guide individuals on a transformative journey towards optimal fitness, well-being, and self-empowerment.</p>
        <br>
        <h4>Connect with us</h4>
        <a href="https://www.tiktok.com/@jacklouistok"><img src="/images/social/016-tiktok.png"></a>
        <a href="https://www.instagram.com/jacklouispt/"><img src="/images/social/011-instagram.png"></a>
        <h4>Connect with Jack</h4>
        <a href="https://www.tiktok.com/@jackstoane4"><img src="/images/social/016-tiktok.png"></a>
        <a href="https://instagram.com/jackstoane?igshid=MzRlODBiNWFlZA=="><img src="/images/social/011-instagram.png"></a>
        <h4>Connect with Louis</h4>
        <a href="https://www.tiktok.com/@louisbenfield"><img src="/images/social/016-tiktok.png"></a>
        <a href="https://instagram.com/louisbenfield_?igshid=MzRlODBiNWFlZA=="><img src="/images/social/011-instagram.png"></a>
        <br><br>
        <p>JACK LOUIS PT LIMITED © 2023. All rights reserved.</p>
      </div>
    </div>
  </footer>';
}


function pdo_connect_mysql() {
    // Update the details below with your MySQL details
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'jacklou1_dbUSER';
    $DATABASE_PASS = 'murkynight77';
    $DATABASE_NAME = 'jacklou1_phplogin';
    try {
    	return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
    	// If there is an error with the connection, stop the script and display the error.
    	exit('Failed to connect to database!');
    }
}

function display_cart() {
  // Get the number of items in the shopping cart, which will be displayed in the header.
$num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
echo <<<EOT
<div class="link-icons">
  <a href="/show-cart.php">
    <img src="/images/cart.png">
    <span>$num_items_in_cart</span>
  </a>
</div>

EOT;
}


function getExercisesForDay($programmeID, $dayNumber, $pdo) {
    $query = "SELECT * FROM Exercise WHERE programme_id = :programme_id AND day_number = :day_number";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":programme_id", $programmeID, PDO::PARAM_INT);
    $stmt->bindParam(":day_number", $dayNumber, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLicenseCount($pdo, $username, $product_id) {
    $queryLicense = "SELECT count(*) FROM License, Product, Account WHERE Product.product_id = License.product_id AND Account.account_id = License.account_id AND Account.username = :username AND License.product_id = :product_id";

    $stmtLicense = $pdo->prepare($queryLicense);
    $stmtLicense->bindParam(":product_id", $product_id, PDO::PARAM_INT);
    $stmtLicense->bindParam(":username", $username, PDO::PARAM_STR);
    $stmtLicense->execute();

    return $stmtLicense->fetchColumn();
}

function getAccountIDFromUsername($pdo, $username) {
    $queryAccID = "SELECT account_id FROM Account WHERE Account.username = :username";

    $stmtAccID = $pdo->prepare($queryAccID);
    $stmtAccID->bindParam(":username", $username, PDO::PARAM_STR);
    $stmtAccID->execute();

    return $stmtAccID->fetchColumn();
}

function getDifficultyFromProgrammeID($pdo, $programme_id) {
    $queryProgID = "SELECT fitness_level FROM Programme WHERE Programme.programme_id = :programme_id";

    $stmtProgID = $pdo->prepare($queryProgID);
    $stmtProgID->bindParam(":programme_id", $programme_id, PDO::PARAM_STR);
    $stmtProgID->execute();

    return $stmtProgID->fetchColumn();
}

function getProductDetails($product_id) {
    $pdo = pdo_connect_mysql(); // Establish a database connection using PDO

    $query = "SELECT name, price, img FROM Product WHERE product_id = :product_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':product_id', $product_id);
    $stmt->execute();

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    $pdo = null; // Close the database connection

    return $product;
}

function fetchProgramsForLevelAndCategory($pdo, $fitnessLevel, $category) {
    $query = "SELECT * FROM Programme WHERE fitness_level = :fitnessLevel AND category = :category";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":fitnessLevel", $fitnessLevel, PDO::PARAM_STR);
    $stmt->bindParam(":category", $category, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function createHtmlEmail($title, $message) {
    $logoUrl = 'http://jacklouispt.com/images/logo.png';
    $html = '<html>
    <head>
        <style>
            body {
                font-family: Avenir, Arial, sans-serif;
                background-color: #f5f5f5;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: white;
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
            }
            .logo {
                width: 150px;
                display: block;
                margin: 0 auto;
            }
            .message {
                margin-bottom: 20px;
            }
            .footer {
                text-align: center;
                color: #999;
            }
            .footer a {
                color: #333;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img class="logo" src="' . $logoUrl . '" alt="JackLouis PT Logo">
                <h1>' . $title . '</h1>
            </div>
            <div class="message">
                <p>' . $message . '</p>
            </div>
            <div class="footer">
                <p>jacklouispt sets a new standard in the fitness industry, offering unparalleled expertise that guide individuals on a transformative journey towards optimal fitness, well-being, and self-empowerment.</p>
                <p>' . date('Y') . ' &copy; ' . 'JACK LOUIS PT LIMITED</p>
            </div>
        </div>
    </body>
    </html>';

    return $html;
}

function pageWithOneMsg($title, $message) {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>' . $title . '</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles-global.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                font-family: Avenir, Arial, sans-serif;
                background-color: #f5f5f5;
            }
            .section {
                height: 70vh;
                margin: 30px;
            }
        </style>
    </head>
    <body>
        <!-- Top bar -->
        ';
    displayTopBar();

    echo '
        <div class="section">
            <h2>' . $title . '</h2>
            <p>___<br>' . $message . '</p>
        </div>

        <!-- Display the footer content within the HTML string -->
        ';
    displayFooter();

    echo '
    </body>
    </html>';
}


function getVideoFilePath($file) {
    // Assuming the video files are stored in /videos/exercise-videos directory
    $videoFilePath = "videos/exercise-videos/" . $file;

    return $videoFilePath;
}

?>

<?php
session_start();


include "functions.php";
$pdo = pdo_connect_mysql();

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["session_id"])) {
    header("Location: dashboard.php");
    exit();
}

$username = $_SESSION["name"];
$account_id = getAccountIDFromUsername($pdo, $username);
$programme_id = $_GET['programme_id'];
$session_id = $_GET["session_id"];
$day_number = $_GET["day_number"];
$exercise_number = $_GET["exercise_number"];



// Check if the user has permission to view this programme
$queryCheckPermission = "
    SELECT COUNT(*) AS permission_count
    FROM ProgrammeSession
    WHERE account_id = :account_id AND programme_id = :programme_id AND session_id = :session_id";
$stmtCheckPermission = $pdo->prepare($queryCheckPermission);
$stmtCheckPermission->bindParam(":account_id", $account_id, PDO::PARAM_INT);
$stmtCheckPermission->bindParam(":programme_id", $programme_id, PDO::PARAM_INT);
$stmtCheckPermission->bindParam(":session_id", $session_id, PDO::PARAM_INT);
$stmtCheckPermission->execute();
$permissionCount = $stmtCheckPermission->fetchColumn();

if ($permissionCount === 0) {
    header("Location: dashboard.php");
    exit();
}

// Extract the video from the database
$queryVideo = "SELECT video, name FROM Exercise WHERE programme_id = :programme_id AND day_number = :day_number AND exercise_number = :exercise_number";
$stmtVideo = $pdo->prepare($queryVideo);
$stmtVideo->bindParam(":programme_id", $programme_id, PDO::PARAM_INT);
$stmtVideo->bindParam(":day_number", $day_number, PDO::PARAM_INT);
$stmtVideo->bindParam(":exercise_number", $exercise_number, PDO::PARAM_INT);
$stmtVideo->execute();
$video = $stmtVideo->fetch(PDO::FETCH_ASSOC);

$exercise_name = $video['name'];
$file = $video['video'];


?>
<!DOCTYPE html>
<html>
<head>
    <title>jacklouispt - Programme Review</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles-global.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        .video-container {
            max-width: 100%;
        }
        video {
            width: 100%;
            height: auto;
        }

        @media (min-width: 650px) {
          video {
            height: 70vh;

          }
        }
    </style>
</head>
<body>
    <!-- Top bar -->
    <?php displayTopBar(); ?>

    <div class="page-heading">
        <a href="programme-overview.php?session_id=<?php echo $session_id; ?>">Back to Programme ></a>
        <h1><?php echo $exercise_name; ?></h1>
    </div>

    <!-- Video container -->

    <div class="box">
    <div class="video-container">
        <video controls autoplay>
            <source src="<?php echo $file; ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
  </div>

    <?php displayFooter(); ?>
</body>
</html>

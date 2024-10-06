<?php
// video.php
session_start();

include "functions.php";
$pdo = pdo_connect_mysql();

// ... (authentication checks)

// Get the expiration time and token from the URL
$expires = isset($_GET['expires']) ? $_GET['expires'] : 0;
$token = isset($_GET['token']) ? $_GET['token'] : '';

// Validate the token
$secretKey = 'Password123!'; // Change this to your actual secret key
$expectedToken = hash_hmac('sha256', $expires, $secretKey);

if ($expires < time() || $token !== $expectedToken) {
    // URL has expired or token doesn't match, deny access
    header('HTTP/1.1 403 Forbidden');
    echo 'Access denied.';
    exit();
}

$programme_id = $_GET['programme_id'];
$day_number = $_GET['day_number'];
$exercise_number = $_GET['exercise_number'];

// Get the video file path based on programme_id, day_number, and exercise_number
$videoFilePath = getVideoFilePath($programme_id, $day_number, $exercise_number);

// Serve the video with appropriate headers
header('Content-Type: video/mp4');
header('Content-Length: ' . filesize($videoFilePath));
readfile($videoFilePath);
exit();
?>

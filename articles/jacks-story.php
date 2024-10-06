<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// We need to use sessions, so you should always start sessions using the below code.
session_start();
// Include functions and connect to the database using PDO MySQL
include "../functions.php";
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
    <title>jacklouispt - Jack's Story</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles-global.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php setFavicon(); ?>

    <style>

    .section {
      height: auto;
    }


    h1 {
      text-align: left;
      font-family: Avenir, sans-serif;
      font-weight: bold;
    }

    h2 {
      font-size: 21px;
      font-family: Avenir, sans-serif;
      font-weight: bold;
    }

    .caption p {
      font-family: avenir;
      color: gray;
    }

    .story img {
      width: 100%;
      margin-top: 10px;
    }

    </style>

</head>




<body>

    <!-- Top bar -->
    <?php displayTopBar(); ?>




  <div class="story">
  <div class="section">
  <div class="box">

      <h1>Resilience Unveiled: From Adversity to Athletic Triumph</h1>
      <div class="caption">
        <p>2023-08-21</p>
      </div>





    <img id="story-image" src="../images/articles/jacks-story/main-blurred.jpg">
    <div class="caption">
      <p>| Click the image to reveal the unblurred version.</p>
    </div>
    <br>
    <h2>By Jack Stoane</h2>
    <div class="caption">
      <p>jacklouispt Stories | Edinburgh</p><br>
    </div>

    <p>At the age of 16, an unfortunate incident occurred when I was returning home from a night out — I was subjected to an assault. This experience had a profound impact on my mental well-being, eroding my self-assurance to the point where even simple tasks like leaving the house became daunting. During this challenging period, a gym conveniently situated near my home came to my attention. Following my daily school routine, I would make my way there each evening. At that time, I didn't have a specific plan in mind; rather, the gym provided a sense of security and a safe place for me.<br><br>
Guided by a genuine desire for change, I embarked on my gym visits, navigating through the various machines without a clear understanding of their intended functions. Gradually, I started delving into research every night, uncovering insights about muscle growth and optimal training routines. As my efforts began to yield visible progress, a renewed sense of confidence blossomed within me. This positive shift in my self-perception culminated in a remarkable achievement: at the age of 18, I managed to match the Scottish bench press record.<br><br>
Today, at the age of 20, I stand proud of the physique I've sculpted and the wealth of knowledge I've acquired on this transformative journey. This transformation has been more than just physical; it has rekindled a level of confidence I never thought I could regain. Looking back, it's difficult to think where I might be now if not for the pivotal role the gym played in my life.
</p>


  </div>
  </div>
  </div>





  <?php displayFooter(); ?>



</body>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const storyImage = document.getElementById("story-image");
  let isBlurred = true;

  storyImage.addEventListener("click", function() {
    if (isBlurred) {
      storyImage.src = "../images/articles/jacks-story/main.jpg";
      isBlurred = false;
    } else {
      storyImage.src = "../images/articles/jacks-story/main-blurred.jpg";
      isBlurred = true;
    }
  });
});
</script>
</html>

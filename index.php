<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();

// Include functions and connect to the database using PDO MySQL
include 'functions.php';
$pdo = pdo_connect_mysql();

// Function to send the email
function sendEmail($to, $subject, $message, $fromEmail, $fromName) {
    $headers = "From: $fromName <$fromEmail>\r\n";
    $headers .= "Reply-To: $fromEmail\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";

    return mail($to, $subject, $message, $headers);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $name = $_POST["name"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // Validate the data (you can add more validation if needed)
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($name) && !empty($subject) && !empty($message)) {
        // Email the form data to jacklouispt@gmail.com
        $to = "jacklouispt@gmail.com";
        $fromEmail = $email;
        $fromName = $name;

        // Email content
        $email_message = "
        <html>
        <style>body {font-family: Avenir;}</style>
        <body>
            <h2>Website Enquiry</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message:</strong></p>
            <p>$message</p>
        </body>
        </html>
        ";

        // Send the email
        if (sendEmail($to, $subject, $email_message, $fromEmail, $fromName)) {
            // Email sent successfully
            echo "<script>alert('Your message has been sent successfully!');</script>";
        } else {
            // Failed to send email
            echo "<script>alert('Failed to send email. Please try again later.');</script>";
        }
    } else {
        // Form data validation failed
        echo "<script>alert('Please fill in all required fields and provide a valid email address.');</script>";
    }
}
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
    <title>jacklouispt</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles-global.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php setFavicon(); ?>



    <style>


        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }



        /* Carousel */
        .carousel-inner .item {
            height: 600px; /* Adjust the height as desired */
        }

        .carousel-inner img {
            display: flex;
            justify-content: center;
        }

        .carousel-caption {
            position: absolute;
            left: 60%; /* Adjust the value to position the text horizontally */
            top: 40%; /* Center the text vertically */
            transform: translate(-50%, -50%);
            text-align: left;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            width: 40%;
        }



        .carousel-caption ul {
            font-size: 13pt;
        }

        .carousel-inner .item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6));
        }

        .carousel-inner .item img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        .carousel-caption h1 {
            font-family: Impact, Avenir, 'Arial Black', 'Roboto Condensed', Verdana, Arial;
            font-weight: bold;
            font-size: 48pt;
        }

        .plan-image-desktop img{
            height: 200px;
        }

        #plans .two-column {
            height: 470px;
        }

        #reviewer {
            float: left;
            text-align: center;
            font-family: avenir;
            font-weight: bold;
            margin-top: 10px;
        }


        #reviewer img {
            height: 200px;
            padding: 20px;
        }


        #review p {
            font-size: 40px;
            font-style: italic;
            font-family: "Avenir Next Condensed"

        }





        /* Media queries for smaller viewports */
        @media (max-width: 1500px) {

            .carousel-caption h1 {
                font-size: 36pt; /* Adjust the font size for smaller viewports */
            }

            .welcome-message {
                font-size: 12pt;
            }

            #plans .two-column {
              min-height: 660px;
            }
        }

        /* Media queries for smaller viewports */
        @media (max-width: 1023px) {

            .carousel-caption {
                width: 80%; /* Adjust the width for smaller viewports */
                top: 40%
            }

            .carousel-caption h1 {
                font-size: 33pt; /* Adjust the font size for smaller viewports */
            }

            .carousel-inner .item {
                height: 500px; /* Adjust the height as desired */
            }

            .welcome-message {
                font-size: 10pt;
            }

            .plan-image-desktop img{
                display: none;
            }

            #plans .two-column {
              min-height: 670px;
            }

            #review p {
                font-size: 33px;
                font-style: italic;
                font-family: "Avenir Next Condensed"
            }


        }


        @media (min-width: 491px) {
            .mobile-intro-img {
                display: none;
            }

            .mobile-carousel {
               display: none;
            }
        }

        @media (max-width: 490px) {

            .carousel-caption {
                width: 90%; /* Adjust the width for even smaller viewports */
                left: 52%;
                top: 35%;
            }

            .desktop-carousel {
               display: none !important;
            }

            .carousel-caption h1 {
                font-size: 32pt; /* Adjust the font size for even smaller viewports */
            }

            .carousel-caption h3 {
                font-size: 15pt; /* Adjust the font size for even smaller viewports */
            }

            .carousel-caption ul {
                font-size: 12.5pt;
            }

            .carousel-caption a {
                font-size: 12.5pt;
            }

            .carousel-inner .item {
                height: 500px; /* Adjust the height as desired */
            }


            #intro-images {
                display: none;
            }
            .plan-image-desktop {
                display: none;
            }

            #plans .two-column {
              min-height: 700px;
            }

            #reviewer {
                width: 100%;
                text-align: center;
                font-family: avenir;
                font-weight: bold;
            }

            #reviewer img {
                height: 100px;
                padding: 20px;
            }

            #review p {
                font-size: 20px;
                font-style: italic;
                font-family: "Avenir Next Condensed"
            }
        }
    </style>
</head>
<body>
    <!-- Top bar -->
    <?php displayTopBar(); ?>

    <!-- Carousel -->

        <div id="myCarousel" class="carousel slide" data-ride="carousel">
          <!-- Indicators -->
          <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
          </ol>

          <!-- Wrapper for slides -->
          <div class="carousel-inner">

            <div class="item active">



              <img class="desktop-carousel" src="images/index/carousel1.jpg" alt="Muscle Gain" style="width:100%; height:100%; vertical-align: middle;">
              <img class="mobile-carousel" src="images/index/carousel1-mobile.jpg" alt="Muscle Gain" style="width:100%; vertical-align: middle;">

              <div class="carousel-caption">
                <h1>Muscle Growth Programmes</h1>
                <h3>Unleash Your Inner Beast: Unlock Maximum Strength and Size.</h3>
                <ul><li>Meal Plans</li> <li>Exercise Explanation Videos</li><li>Exercise Logging</li><li>Accessible Demonstrations</li><li>Progress Tracking</li><li>One payment. Multiple 3, 4 <br>and 5 day programmes.</li></ul>
                <a href="cart.php?action=add&product_id=1">Add to cart ></a>
              </div>
            </div>

            <div class="item">
              <img class="desktop-carousel" src="images/index/carousel2.jpg" alt="Fat Loss" style="width:100%; height:100%; vertical-align: middle;">
              <img class="mobile-carousel" src="images/index/carousel2-mobile.jpg" alt="Fat Loss" style="width:100%; vertical-align: middle;">
              <div class="carousel-caption">
                <h1>Fat Loss Programmes</h1>
                <h3>Rewrite Your Story: Unleash the New, Fitter You.</h3>
                <ul><li>Meal Plans</li> <li>Exercise Explanation Videos</li><li>Exercise Logging</li><li>Accessible Demonstrations</li><li>Progress Tracking</li><li>One payment. Multiple 3, 4 <br>and 5 day programmes.</li></ul>

                <a href="cart.php?action=add&product_id=2">Add to cart ></a>
              </div>
            </div>

            <div class="item">
              <img src="images/index/carousel3.jpg" alt="Third carousel" style="width:100%;">
              <div class="carousel-caption">
                <h1>Two-to-One Coaching</h1>
                <h3>Personalised Workout & Diet Programmes.</h3>
                <ul><li>2 to 1 WhatsApp Group with us</li> <li>Weekly Check-ins</li><li>Video Calls</li><li>Progress Tracking and Analysis</li><li>Expert Support for Your Fitness Journey.</li></ul>

                <a href="#enquiry-form">Enquire ></a>
              </div>
            </div>

          </div>


        </div>


    <!-- Introduction section -->
    <div class="section">
        <div id="intro-images">
        <div class="two-column">

            <div class="two-column">
                <img src="images/index/image1.jpg" alt="Image 1" style="height: fix; width:80%; object-fit: cover; padding: 10px; max-height: 376px;">
            </div>

            <div class="two-column">
                <img src="images/index/image2.jpg" alt="Image 2" style="height: fix; width:80%; object-fit: contain; padding: 10px; max-height: 376px;">
            </div>


        </div>
        </div>

        <div class="two-column">
            <div class="text">
                <h2>Join us today and unlock your full potential!</h2>
                <p>___</p>
                <p>Welcome to jacklouispt - your ultimate destination for fitness programs designed to help you achieve your goals. Founded by fitness enthusiasts Jack and Louis, our brand is dedicated to empowering individuals on their fitness journeys. Whether you're looking to shed those extra pounds or build lean muscle mass, we've got you covered. </p>

            </div>
        </div>


    </div>

    <div class="mobile-intro-img">
    <div class="section">
        <img src="images/index/image1.jpg" style="width: 100%;">
    </div>
    </div>

    <div id="plans">
    <div class="section">
        <div class="two-column" style="background-color: lightgrey; padding: 5px; margin-right: 10px;">
          <div class="text">
            <h2>Muscle Growth Programmes</h2>
            <h3>Lifetime Access for £39.99</h3>
            <p>___</p>
            <p>Presenting our groundbreaking Lifetime Muscle Growth Programmes – the ultimate solution for achieving and maintaining your desired muscular physique! With a single purchase, unlock a treasure trove of 3, 4, and 5-day training regimens, meticulously crafted for whatever stage you are at in your journey.<br><br> Experience three distinct programme options, ensuring optimal progress every step of the way. Fuel your gains with our diverse meal plans, thoughtfully customised to align with your calorie targets. Seize the opportunity to sculpt your body with precision, supported by proven strategies and unwavering guidance. Elevate your training with our multi-stage approach and embark on a lifelong journey of muscle mastery.</p>
<a href="cart.php?action=add&product_id=1">Add to cart ></a>
</div>
            <div class="plan-image-desktop">
              <img src="images/index/muscle-growth.jpg" style="float: right;">
            </div>
        </div>

        <div class="two-column" style="background-color: lightgrey; padding: 5px; margin-bottom: 30px;">
          <div class="text">
            <h2>Fat Loss Programmes</h2>
            <h3>Lifetime Access for £39.99</h3>
            <p>___</p>
            <p>Introducing our revolutionary Lifetime Fat Loss Programmes – your one-stop solution for sustainable weight management! With a single purchase, unlock access to an array of expertly crafted 3, 4, and 5-day programmes, tailored for every fitness level.<br><br> Our meticulously designed regimen includes diverse meal plans, precisely calibrated to meet your calorie requirements, ensuring effective fat loss. Embark on a transformative journey towards a healthier you, guided by proven strategies and comprehensive support. Elevate your training experience with our multi-stage programmes and embrace a lifetime of fitness success.</p>
<a href="cart.php?action=add&product_id=2">Add to cart ></a>


</div>
<div class="plan-image-desktop">
  <img src="images/index/fat-loss.jpg" style="float: right;">
</div>

</div>


    </div>
    </div>




    <!-- Rest of the website content -->
    <div class="section">

        <div class="two-column">
            <div class="text">
                <h2>Jack's Story</h2>
                <p>___</p>
                <p>At the age of 16, an unfortunate incident occurred when I was returning home from a night out — I was subjected to an assault. This experience had a profound impact on my mental well-being, eroding my self-assurance to the point where even simple tasks like leaving the house became daunting. During this challenging period, a gym conveniently situated near my home...</p>
                <a href="articles/jacks-story.php">Read Full Story ></a>

            </div>
        </div>




        <div id="intro-images">
        <div class="two-column">
          <div class="two-column">
              <img src="images/index/jack1.jpg" alt="Image 1" style="height: fix; width:80%; object-fit: cover; padding: 15px; max-height: 400px;">
          </div>



          <div class="two-column">
              <img src="images/index/jack2.jpg" alt="Image 2" style="height: fix; width:80%; object-fit: cover; padding: 15px; max-height: 400px;">
          </div>
        </div>
      </div>
      </div>

      <div class="mobile-intro-img">
      <div class="section">
          <img src="images/index/jack1.jpg" style="width: 100%; padding: none">
      </div>
      </div>

      <div class="section">

          <div class="two-column">
              <div class="text">
                  <h2>Louis' Story</h2>
                  <p>___</p>
                  <p>I used to always be seen as the typical lanky teenager, lacking any semblance of confidence. Over the past several years, I've explored every avenue to attain my desired physique. While I may not have fully achieved my dream physique yet, I've successfully undergone a physical transformation during this time. My best friend Jack and I aspire to aid individuals who find themselves in the very same predicament we were in a few years back.</p>

              </div>
          </div>


          <div id="intro-images">
          <div class="two-column">

            <div class="two-column">
                <img src="images/index/louis1.jpg" alt="Image 1" style="height: fix; width:80%; object-fit: cover; padding: 15px; max-height: 400px;">
            </div>

            <div class="two-column">
                <img src="images/index/louis2.jpg" alt="Image 2" style="height: fix; width:80%; object-fit: cover; padding: 15px; max-height: 400px;">
            </div>
          </div>
        </div>
        </div>

        <div class="mobile-intro-img">
        <div class="section">
            <img src="images/index/louis1.jpg" style="width: 100%;">
        </div>
        </div>


        <div class="section" style="max-width: 80%; margin: 0 auto; padding: 20px; border-radius: 5px;">
          <h2 style="text-align: center; color: #333; margin-bottom: 20px;">Want to make an enquiry?</h2>
          <form action="" method="post" id="enquiry-form">
            <label for="email" style="display: block; margin-bottom: 5px; color: #555; font-weight: bold;">Email:</label>
            <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px; font-size: 14px;">

            <label for="name" style="display: block; margin-bottom: 5px; color: #555; font-weight: bold;">Name:</label>
            <input type="text" id="name" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px; font-size: 14px;">

            <label for="subject" style="display: block; margin-bottom: 5px; color: #555; font-weight: bold;">Subject:</label>
            <input type="text" id="subject" name="subject" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px; font-size: 14px;">

            <label for="message" style="display: block; margin-bottom: 5px; color: #555; font-weight: bold;">Message:</label>
            <textarea id="message" name="message" rows="5" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px; font-size: 14px;"></textarea>

            <input type="submit" value="Submit" style="background-color: #E10101; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
          </form>
        </div>

        <?php
          displayFooter();
        ?>











    <script>
        // Carousel functionality


        const carouselContainer = document.querySelector('.carousel-container');
        const carouselItems = document.querySelectorAll('.carousel-item');
        const carouselIndicators = document.querySelectorAll('.carousel-indicators .indicator');

        let currentItem = 0;
        let isAnimating = false;

        // Function to show a particular carousel item
        const showItem = (index) => {
            if (isAnimating) return;

            isAnimating = true;

            carouselItems.forEach((item) => {
                item.classList.remove('active');
                item.style.opacity = '0';
            });

            carouselIndicators.forEach((indicator) => {
                indicator.classList.remove('active');
            });

            carouselItems[index].classList.add('active');
            carouselIndicators[index].classList.add('active');

            setTimeout(() => {
                carouselItems[index].style.opacity = '1';
                isAnimating = false;
            }, 100);
        };

        // Function to move to the next carousel item
        const nextItem = () => {
            if (isAnimating) return;

            currentItem = (currentItem + 1) % carouselItems.length;
            carouselContainer.style.transform = `translateX(-${currentItem * 33.33}%)`;
            showItem(currentItem);
        };

        // Function to move to the previous carousel item
        const prevItem = () => {
            if (isAnimating) return;

            currentItem = (currentItem - 1 + carouselItems.length) % carouselItems.length;
            carouselContainer.style.transform = `translateX(-${currentItem * 33.33}%)`;
            showItem(currentItem);
        };

        // Click event listener for carousel indicators
        carouselIndicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                if (index === currentItem) return;

                currentItem = index;
                carouselContainer.style.transform = `translateX(-${currentItem * 33.33}%)`;
                showItem(currentItem);
            });
        });

        // Auto-scroll the carousel every 5 seconds
        setInterval(nextItem, 5000);
    </script>
</body>
</html>

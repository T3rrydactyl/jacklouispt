<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();

// Include functions and connect to the database using PDO MySQL
include 'functions.php';
$pdo = pdo_connect_mysql();
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
    <title>JackLouisPT - Account Registration</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles-global.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php setFavicon(); ?>

    <style>

    .section {
      height:70vh;
    }


        }
    </style>
</head>
<body>
    <!-- Top bar -->
    <?php displayTopBar(); ?>


        <div class="section" style="margin: 0 auto; border-radius: 5px;">
          <div class="login-register">
			<form action="register.php" method="post" autocomplete="off">
        <h2 style="text-align: center; color: #333; margin-bottom: 20px; width: 100%;">Account Registration</h2>
				<label for="username">
					<i class="fas fa-user"><img src="images/account/username.png"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"><img src="images/account/password.png"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<label for="email">
					<i class="fas fa-envelope"><img src="images/account/email.png"></i>
				</label>
				<input type="email" name="email" placeholder="Email" id="email" required>
				<input type="submit" value="Register">
			</form>
		</div>
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

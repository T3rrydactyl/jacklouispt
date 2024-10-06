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
    <title>jacklouispt - Shopping Cart</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles-global.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php setFavicon(); ?>

    <style>
        table {
            font-family: Avenir, sans-serif;
            font-size: 16px;
        }

        .mobile-table {
            display: none;
        }

        .section {
            height: auto;
            min-height: 70vh;
        }

        .column {
            float: left;
            padding: 10px;
        }

        .left {
            width: 70%;
        }

        .right {
            width: 30%;
            background-color: #f2f2f2;
            padding: 15px;
            height: auto;
        }

        .right h2 {
            margin-top: 0;
        }

        .remove-item {
            color: red;
            cursor: pointer;
        }

        .item-image {
            max-width: 200px;
            object-fit: cover;
        }

        /* Styles for mobile */
        @media (max-width: 768px) {
            .left,
            .right {
                width: 100%;
            }

            .row {
            flex-direction: column;
        }

        .column.left,
        .column.right {
            width: 100%;
        }

        .right {
            height: auto; /* Adjust height for content */
        }

        .column.right {
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .table-responsive {
            overflow-x: auto; /* Allow horizontal scrolling for small screens */
        }

        .table {
            width: 100%; /* Expand the table to full width */
        }

        /* Center the table header text for better readability */
        th {
            text-align: center;
        }

        /* Center and stack the product info for mobile */
        .product-info {
            text-align: left;
            display: block;
        }

        .remove-item-cell {
            text-align: center;
        }

        .item-image {
            max-width: 60px;
        }

        .product-price, th {
            display: none;
        }

        .mobile-table {
            display: contents;
        }

        /* Overwrites bootstrap.css */
        .table-responsive>.table>tbody>tr>td, .table-responsive>.table>tbody>tr>th, .table-responsive>.table>tfoot>tr>td, .table-responsive>.table>tfoot>tr>th, .table-responsive>.table>thead>tr>td, .table-responsive>.table>thead>tr>th {
            white-space: normal;
        }
      }
    </style>
</head>
<body>
    <!-- Top bar -->
    <?php displayTopBar(); ?>

    <div class="page-heading">
        <h1>Shopping Cart</h1><br></div>
    </div>

    <div class="section">
        <div class="row">
            <div class="column left">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price (£)</th>
                                <th>Total (£)</th>
                                <th class="remove-item-cell"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Connect to your MySQL database
                            $conn = mysqli_connect('localhost', 'jacklou1_dbUSER', 'murkynight77', 'jacklou1_phplogin');

                            // Check if the connection was successful
                            if (!$conn) {
                                die("Connection failed: " . mysqli_connect_error());
                            }

                            // Check if the cart is not empty
                            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $item) {
                                    $product_id = $item['product_id'];
                                    $quantity = $item['quantity'];

                                    // Retrieve the product details from the Product table
                                    $sql = "SELECT name, price, img, `desc`, digital FROM Product WHERE product_id = $product_id";
                                    $result = mysqli_query($conn, $sql);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        $row = mysqli_fetch_assoc($result);
                                        $product_name = $row['name'];
                                        $product_description = $row['desc'];
                                        $product_price = $row['price'];
                                        $total_price = $product_price * $quantity;
                                        $product_img = $row['img'];
                                        $is_digital = $row['digital'];

                                        // Display the product details in the table
                                        echo "<tr class='product-row'>";
                                        echo "<td class='product-image'><img class='item-image' src='$product_img' alt='Product Image'></td>";
                                        echo "<td class='product-info'>$product_name <br><br>$product_description</td>";
                                        echo "<td class='product-info'><div class='mobile-table'>Quantity: </div>";
                                        echo $is_digital ? "$quantity (max)" : $quantity;
                                        echo "</td>";
                                        echo "<td class='product-info product-price'>$product_price</td>";
                                        echo "<td class='product-info'><div class='mobile-table'>Price: £</div>$total_price</td>";
                                        echo "<td class='remove-item-cell remove-item' data-product-id='$product_id'>&times;</td>";
                                        echo "</tr>";
                                    }
                                }
                            }

                            // Close the database connection
                            mysqli_close($conn);
                            ?>
                        </tbody>
                    </table>
                </div>
                <a href="cart.php?action=empty">Empty Cart ></a>
            </div>

            <div class="column right">
                <h2>Cart Summary:</h2>
    <?php
    // Calculate and display the grand total
    $numItems = 0;
    $grandTotal = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];

            // Retrieve the product price from the Product table
            $conn = mysqli_connect('localhost', 'jacklou1_dbUSER', 'murkynight77', 'jacklou1_phplogin');
            $sql = "SELECT price FROM Product WHERE product_id = $product_id";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $product_price = $row['price'];
                $total_price = $product_price * $quantity;
                $grandTotal += $total_price;
                $numItems += $quantity;
            }

            mysqli_close($conn);
        }
    }
    echo "<h3>Number of items: $numItems</h3>"; // Display number of items
    echo "<h3>Grand Total: £$grandTotal</h3>";
    ?>

    <p>___ <br>Postage costs will be calculated at checkout.<?php if (!isset($_SESSION['loggedin'])) {
        // User is not logged in, display default menu for guests
        echo "<br>You must be logged in before proceeding.";
    } ?></p><br>


    <button class="btn btn-primary" id="checkoutButton" <?php echo (empty($_SESSION['cart'])) ? 'disabled' : ''; ?> onclick="location.href='checkout.php'">Proceed to checkout</button><br>

    <a href="index.php">Continue Shopping ></a>

            </div>
        </div>
    </div>

    <?php displayFooter(); ?>

</body>

<script>
$(document).ready(function() {
    // Add click event listener to remove items
    $('.remove-item').click(function() {
        var productId = $(this).data('product-id');
        // Send an AJAX request to remove the item from the cart
        $.ajax({
            url: 'cart.php',
            method: 'POST',
            data: { action: 'remove', product_id: productId },
            success: function(response) {
                // Log response for debugging
                console.log(response);
                // Reload the page after successful removal
                window.location.reload();
            },
            error: function(xhr, status, error) {
                // Log error for debugging
                console.error(error);
            }
        });
    });
});


$(document).ready(function() {
    // Add click event listener to remove items
    $('.remove-item').click(function() {
        var productId = $(this).data('product-id');
        // ...
    });

    // Add click event listener to checkout button
    $('#checkoutButton').click(function() {
        // Check if the user is logged in
        console.log('User logged in: <?php echo isset($_SESSION['loggedin']) ? 'true' : 'false'; ?>');

        <?php if (!isset($_SESSION['loggedin'])) : ?>
            // Display an alert message
            alert("You must be logged in before checking out.");
            return false; // Prevent the checkout action
        <?php endif; ?>

        // Continue with the checkout process
        // ...
    });
});
</script>

</html>

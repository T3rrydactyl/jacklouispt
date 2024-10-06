<?php

session_start();

if (!isset($_SESSION["loggedin"])) {
    header("Location: registration-form.php");
    exit();
}

// Include Stripe PHP library
require_once('stripe-php-master/init.php');

// Set your Stripe API keys
\Stripe\Stripe::setApiKey('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');

// Include other necessary functions and configurations
include 'functions.php'; // Update the path as needed

// Create a Stripe checkout session
$lineItems = array();
$cartTotal = 0;
$productIDs = array(); // Store product IDs for licensing

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];

        // Retrieve the product details from the database
        // Replace this with your actual database retrieval logic
        $product = getProductDetails($product_id); // Implement getProductDetails() in your functions.php

        if ($product) {
            $price = $product['price'] * 100; // Stripe uses cents
            $cartTotal += $price * $quantity;

            // Convert relative image path to absolute URL
            $image_url = 'https://jacklouispt.com/' . $product['img'];

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'gbp',
                    'unit_amount' => $price,
                    'product_data' => [
                        'name' => $product['name'],
                        'images' => [$image_url], // Use the absolute URL here
                    ],
                ],
                'quantity' => $quantity,
            ];

            // Stores product IDs for licensing
            $productIDs[] = $product_id;
        }
    }
}

$checkout_session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => $lineItems,
    'mode' => 'payment',
    'success_url' => 'https://jacklouispt.com/purchase-success.php', // Replace with your success URL
    'cancel_url' => 'https://jacklouispt.com/show-cart.php', // Replace with your cancel URL
]);

// Store product IDs in the session for use after successful payment
$_SESSION['productIDs'] = $productIDs;

// Redirect to Stripe checkout page
header("Location: " . $checkout_session->url);

?>

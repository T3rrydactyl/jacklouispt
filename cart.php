<?php
session_start();

// Function to add an item to the cart
function add_to_cart($product_id) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Limit maximum quantity to 1 for products with IDs 1 or 2
        if ($product_id === 1 || $product_id === 2) {
            $_SESSION['cart'][$product_id]['quantity'] = 1;
        } else {
            $_SESSION['cart'][$product_id]['quantity']++;
        }
    } else {
        // If the product is not in the cart, add it
        $_SESSION['cart'][$product_id] = array(
            'product_id' => $product_id,
            'quantity' => 1,
        );
    }
}

function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

function empty_cart() {
    if (isset($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }
}

// Check if the action parameter exists and handle it
if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];

    if ($action === 'add' && isset($_REQUEST['product_id'])) {
        $product_id = intval($_REQUEST['product_id']);
        add_to_cart($product_id);
    } elseif ($action === 'remove' && isset($_REQUEST['product_id'])) {
        $product_id = intval($_REQUEST['product_id']);
        remove_from_cart($product_id);
    } elseif ($action === 'empty') {
        empty_cart();
    }

    // Redirect to the appropriate page after handling the action
    if ($action === 'add' || $action === 'remove' || $action === 'empty') {
        header("Location: show-cart.php");
        exit();
    }
}

// Redirect to the homepage (or any other page) if no specific action is triggered
header("Location: index.php");
exit();
?>

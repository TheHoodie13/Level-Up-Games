<?php

// Start session so we can access the logged‑in account ID
session_start();

// If the user is not logged in, redirect them to the sign‑in page
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../signup_page/signin.php");
    exit;
}

// Load the global bootstrap (defines APP_ROOT, APP_URL, DB_PATH, autoloader, etc.)
require_once __DIR__ . '/../../../bootstrap.php';

// Load database connection using DB_PATH from config.php
$mysqli = require DB_PATH;

// Get the item ID from POST and validate it as an integer
$itemId = filter_input(INPUT_POST, "item_id", FILTER_VALIDATE_INT);

// If item ID is missing or invalid, return to wishlist page
if (!$itemId) {
    header("Location: items-wishlist.php");
    exit;
}

// Convert session account ID to integer for safety
$userId = (int) $_SESSION["user_id"];

// Check if the item already exists in the user's wishlist
$check = $mysqli->prepare("
    SELECT id, quantity
    FROM wishlist
    WHERE user_id = ? AND product_id = ?
");
$check->bind_param("ii", $userId, $itemId);
$check->execute();
$existing = $check->get_result()->fetch_assoc();

// If item exists, increment quantity
if ($existing) {

    $update = $mysqli->prepare("
        UPDATE wishlist
        SET quantity = quantity + 1
        WHERE id = ?
    ");
    $update->bind_param("i", $existing["id"]);
    $update->execute();

} else {
    // Otherwise insert a new wishlist row
    $insert = $mysqli->prepare("
        INSERT INTO wishlist (user_id, product_id, quantity)
        VALUES (?, ?, 1)
    ");
    $insert->bind_param("ii", $userId, $itemId);
    $insert->execute();
}

// Redirect back to the wishlist page
header("Location: wishlist.php");
exit;
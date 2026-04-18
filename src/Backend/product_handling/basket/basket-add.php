<?php

// Always load bootstrap first so APP_ROOT, DB_PATH, APP_URL, and session are available
require_once __DIR__ . '/../../../config/bootstrap.php';

// At this point session_start() should already have been called in bootstrap.php

// If the user is not logged in, redirect them to the sign‑in page
if (!isset($_SESSION["account_id"])) {
    header("Location: ../../account/login.php");
    exit;
}

// Load database connection using DB_PATH from config.php
$mysqli = require DB_PATH;

// Get the item ID from POST and validate it as an integer
$itemId = filter_input(INPUT_POST, "item_id", FILTER_VALIDATE_INT);

// If item ID is missing or invalid, return to basket page
if (!$itemId) {
    header("Location: items-basket.php");
    exit;
}

// Convert session account ID to integer for safety
$userId = (int) $_SESSION["account_id"];

// Check if the item already exists in the user's basket
$check = $mysqli->prepare("
    SELECT id, quantity
    FROM basket
    WHERE account_id = ? AND product_id = ?
");
$check->bind_param("ii", $userId, $itemId);
$check->execute();
$existing = $check->get_result()->fetch_assoc();

// If item exists, increment quantity
if ($existing) {
    $update = $mysqli->prepare("
        UPDATE basket
        SET quantity = quantity + 1
        WHERE id = ?
    ");
    $update->bind_param("i", $existing["id"]);
    $update->execute();
} else {
    // Otherwise insert a new basket row
    $insert = $mysqli->prepare("
        INSERT INTO basket (account_id, product_id, quantity)
        VALUES (?, ?, 1)
    ");
    $insert->bind_param("ii", $userId, $itemId);
    $insert->execute();
}

// Redirect back to the basket page
header("Location: items-basket.php");
exit;

<?php

// Start session so we can access the logged‑in account ID
session_start();

// If the user is not logged in, redirect them to the sign‑in page
if (!isset($_SESSION["account_id"])) {
    header("Location: ../../signup_page/signin.php");
    exit;
}

// Load the global bootstrap (defines APP_ROOT, APP_URL, DB_PATH, autoloader, etc.)
require_once __DIR__ . '/../../../bootstrap.php';

// Load database connection using DB_PATH from config.php
$mysqli = require DB_PATH;

// Get the basket row ID from POST and validate it as an integer
$basketId = filter_input(INPUT_POST, "basket_id", FILTER_VALIDATE_INT);

// If basket_id is missing or invalid, return to basket page
if (!$basketId) {
    header("Location: items-basket.php");
    exit;
}

// Convert session account ID to integer for safety
$userId = (int) $_SESSION["account_id"];

// Delete the basket row ONLY if it belongs to the logged‑in user
$stmt = $mysqli->prepare("
    DELETE FROM basket
    WHERE id = ? AND account_id = ?
");
$stmt->bind_param("ii", $basketId, $userId);
$stmt->execute();

// Redirect back to the basket page
header("Location: items-basket.php");
exit;

?>
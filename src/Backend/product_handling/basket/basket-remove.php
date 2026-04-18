<?php

// Load bootstrap FIRST — this starts the session and defines APP_ROOT, DB_PATH, etc.
require_once __DIR__ . '/../../../config/bootstrap.php';

// If the user is not logged in, redirect them to the sign‑in page
if (!isset($_SESSION["account_id"])) {
    header("Location: ../../account/signin.php");
    exit;
}

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

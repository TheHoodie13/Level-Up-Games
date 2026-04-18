<?php
session_start();
require_once __DIR__ . '/../../config/bootstrap.php';

// Load database connection
$mysqli = require DB_PATH;

// User must be logged in to update details
if (!isset($_SESSION["account_id"])) {
    header("Location: " . APP_URL . "/Backend/account/login.php");
    exit;
}

// Convert session ID to integer for safety
$account_id = (int) $_SESSION["account_id"];

// Read submitted fields
$name  = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");

// Ensure required fields are present
if ($name === "" || $email === "") {
    header("Location: account.php?error_details=" . urlencode("Name and email are required."));
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: account.php?error_details=" . urlencode("Please enter a valid email address."));
    exit;
}

// Check if email is already used by another account
$check = $mysqli->prepare("SELECT id FROM account WHERE email = ? AND id <> ?");
$check->bind_param("si", $email, $account_id);
$check->execute();
$result = $check->get_result();

// If another user has this email, reject update
if ($result->num_rows > 0) {
    header("Location: account.php?error_details=" . urlencode("That email address is already registered."));
    exit;
}

$update = $mysqli->prepare("UPDATE account SET name = ?, email = ? WHERE id = ?");
$update->bind_param("ssi", $name, $email, $account_id);

// If update succeeds, redirect with success message
if ($update->execute()) {
    header("Location: account-details.php?success_details=" . urlencode("Account details updated successfully."));
    exit;
}

// If update fails, redirect with error
header("Location: account-details.php?error_details=" . urlencode("There was a problem updating your account."));
exit;
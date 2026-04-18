<?php
session_start();
require_once __DIR__ . '/../../config/bootstrap.php';

// Load database connection
$mysqli = require DB_PATH;

// User must be logged in
if (!isset($_SESSION["account_id"])) {
    header("Location: " . APP_URL . "/Backend/account/login.php");
    exit;
}

$account_id = (int) $_SESSION["account_id"];

// Fetch current password hash from DB
$stmt = $mysqli->prepare("SELECT password_hash FROM account WHERE id = ?");
$stmt->bind_param("i", $account_id);
$stmt->execute();
$account = $stmt->get_result()->fetch_assoc();

// Read submitted fields
$current_password = $_POST["current_password"] ?? "";
$new_password     = $_POST["new_password"] ?? "";
$confirm_password = $_POST["confirm_password"] ?? "";

// Ensure new password fields are filled
if ($new_password === "" || $confirm_password === "") {
    header("Location: account-details.php?error_password=" . urlencode("Please enter and confirm your new password."));
    exit;
}

// Verify current password matches stored hash
if (!password_verify($current_password, $account["password_hash"])) {
    header("Location: account-details.php?error_password=" . urlencode("Current password is incorrect."));
    exit;
}

// Ensure new passwords match
if ($new_password !== $confirm_password) {
    header("Location: account-details.php?error_password=" . urlencode("New passwords do not match."));
    exit;
}

// Enforce password length
if (strlen($new_password) < 8) {
    header("Location: account-details.php?error_password=" . urlencode("New password must be at least 8 characters."));
    exit;
}

// Require at least one letter
if (!preg_match("/[a-z]/i", $new_password)) {
    header("Location: account-details.php?error_password=" . urlencode("New password must contain at least one letter."));
    exit;
}

// Require at least one number
if (!preg_match("/[0-9]/", $new_password)) {
    header("Location: account-details.php?error_password=" . urlencode("New password must contain at least one number."));
    exit;
}
// Hash the new password
$new_hash = password_hash($new_password, PASSWORD_DEFAULT);

// Update password in DB
$update = $mysqli->prepare("UPDATE account SET password_hash = ? WHERE id = ?");
$update->bind_param("si", $new_hash, $account_id);

// If update succeeds, redirect with success message
if ($update->execute()) {
    header("Location: account-details.php?success_password=" . urlencode("Password updated successfully."));
    exit;
}

// If update fails, redirect with error
header("Location: account-details.php?error_password=" . urlencode("There was a problem updating your password."));
exit;
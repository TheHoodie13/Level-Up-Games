<?php
require_once __DIR__ . '/../../config/bootstrap.php';

$mysqli = require DB_PATH;

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$name || !$email || !$password) {
    header("Location: login.php?error=empty");
    exit;
}

// Check if email exists
$stmt = $mysqli->prepare("SELECT id FROM account WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: login.php?error=exists");
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("INSERT INTO account (name, email, password_hash) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashed);
$stmt->execute();

session_start();
$_SESSION['user_id'] = $stmt->insert_id;
$_SESSION['user_name'] = $name;

header("Location: ../index.php");
exit;

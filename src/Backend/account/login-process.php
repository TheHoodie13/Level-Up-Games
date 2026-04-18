<?php
require_once __DIR__ . '/../../config/bootstrap.php';

$mysqli = require DB_PATH;

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    header("Location: login.php?error=invalid");
    exit;
}

$stmt = $mysqli->prepare("SELECT id, name, password_hash FROM account WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password_hash'])) {

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];

    header("Location: ../../index.php");
    exit;

} else {
    header("Location: login.php?error=invalid");
    exit;
}

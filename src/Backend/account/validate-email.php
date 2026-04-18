<?php

// Load the global bootstrap so APP_ROOT, APP_URL, DB_PATH, autoloader, etc. are available
require_once __DIR__ . '/../../bootstrap.php';

// Load database connection using DB_PATH defined in config.php
$mysqli = require DB_PATH;

// Get email from query string (default to empty string)
$email = $_GET["email"] ?? "";

// Prepare SQL query to check if email exists
$sql = "SELECT id FROM account WHERE email = ?";

// Prepare statement
$stmt = $mysqli->prepare($sql);

// Bind email parameter
$stmt->bind_param("s", $email);

// Execute query
$stmt->execute();

// Get result
$result = $stmt->get_result();

// Email is available if no rows returned
$is_available = ($result->num_rows === 0);

// Return JSON response
header("Content-Type: application/json");
echo json_encode(["available" => $is_available]);
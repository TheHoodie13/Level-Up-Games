<?php
$servername = "your_server_here";
$username = "your_username_here";
$password = "your_password_here";
$database = "your_database_here";

$mysqli = new mysqli($servername, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;

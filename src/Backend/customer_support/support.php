<?php

session_start();

require_once __DIR__ . '/../../config/bootstrap.php';

$mysql = require DB_PATH;

// Redirect if not logged in
if (!isset($_SESSION["account_id"])) {
    header("Location: " . APP_URL . "/Backend/account/login.php");
    exit;
}

$account_id = (int) $_SESSION["account_id"];

// Fetch account email
$stmt = $mysql->prepare("SELECT email FROM account WHERE id = ?");
$stmt->bind_param("i", $account_id);
$stmt->execute();
$result = $stmt->get_result();
$account = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $issue = trim($_POST["issue"]);

$insert = $mysql->prepare(
    "INSERT INTO support_requests (idx_account, description, request_date)
     VALUES (?, ?, CURDATE())"
);
$insert->bind_param("is", $account_id, $issue);
$insert->execute();

    header("Location: " . APP_URL . "/index.php?reported=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/main.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/button.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/account.css">
    <title>Report an Issue</title>
</head>
<body>

<form method="post" class="form">

    <div>
        <input type="text" id="email" name="email"
               placeholder="email"
               value="<?= htmlspecialchars($account["email"]) ?>" required>
        <label for="email">email</label>
    </div>

    <div>
        <textarea id="issue" name="issue" rows="5"
               placeholder="Issue Description" required
               value="<?= htmlspecialchars($account["email"]) ?>"></textarea>
        <label for="issue"></label>
    </div>

    <button type="submit" class="btn-submit">Submit Issue</button>
    <a href="<?= APP_URL ?>/index.php" class="btn-return">Return</a>

</form>

</body>
</html>
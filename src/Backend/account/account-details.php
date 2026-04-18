<?php
session_start();
require_once __DIR__ . '/../../config/bootstrap.php';

$mysqli = require DB_PATH;

// User must be logged in
if (!isset($_SESSION["account_id"])) {
    header("Location: " . APP_URL . "/Backend/account/login.php");
    exit;
}

$account_id = (int) $_SESSION["account_id"];

// Fetch user details
$stmt = $mysqli->prepare("SELECT name, email FROM account WHERE id = ?");
$stmt->bind_param("i", $account_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Messages
$success_details = $_GET["success_details"] ?? null;
$error_details   = $_GET["error_details"] ?? null;

$success_password = $_GET["success_password"] ?? null;
$error_password   = $_GET["error_password"] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Settings</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/account.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/main.css">
</head>

<body>

<h1>Account Settings</h1>

<div class="account-wrapper">

    <form action="update_details.php" method="POST" class="account-form">

        <h2 style="color: var(--page-accent-4);">Update Details</h2>

        <?php if ($success_details): ?>
            <p class="success-message"><?= htmlspecialchars($success_details) ?></p>
        <?php endif; ?>

        <?php if ($error_details): ?>
            <p class="error"><?= htmlspecialchars($error_details) ?></p>
        <?php endif; ?>

        <div>
            <input type="text" name="name" placeholder=" " 
                   value="<?= htmlspecialchars($user["name"]) ?>" required>
            <label>Name</label>
        </div>

        <div>
            <input type="email" name="email" placeholder=" " 
                   value="<?= htmlspecialchars($user["email"]) ?>" required>
            <label>Email</label>
        </div>

        <button type="submit" class="btn-update">Save Changes</button>
    </form>


    <!-- RIGHT SIDE — PASSWORD CHANGE -->
    <form action="update_password.php" method="POST" class="account-form">

        <h2 class="password-title">Change Password</h2>

        <?php if ($success_password): ?>
            <p class="success-message"><?= htmlspecialchars($success_password) ?></p>
        <?php endif; ?>

        <?php if ($error_password): ?>
            <p class="error"><?= htmlspecialchars($error_password) ?></p>
        <?php endif; ?>

        <div>
            <input type="password" name="current_password" placeholder=" " required>
            <label>Current Password</label>
        </div>

        <div>
            <input type="password" name="new_password" placeholder=" " required>
            <label>New Password</label>
        </div>

        <div>
            <input type="password" name="confirm_password" placeholder=" " required>
            <label>Confirm New Password</label>
        </div>

        <button type="submit" class="btn-update">Update Password</button>
    </form>

</div>

<!-- OPTIONAL: Return + Logout buttons -->
<a href="<?= APP_URL ?>/index.php" class="btn-return">Return to Main Page</a>
<a href="<?= APP_URL ?>/Backend/account/logout.php" class="btn-logout">Log Out</a>

</body>
</html>

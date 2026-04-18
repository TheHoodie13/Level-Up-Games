<?php
session_start();
require_once __DIR__ . '/../../config/bootstrap.php';

$mysqli = require DB_PATH;

// Arrays to store validation errors
$login_errors = [];
$signup_errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Determine if this is a signup request
    $is_signup = isset($_POST["signup"]);

    if ($is_signup) {

        // Validate required fields
        if (empty($_POST["name"])) {
            $signup_errors["name"] = "Name is required.";
        }

        if (empty($_POST["email"])) {
            $signup_errors["email"] = "Email is required.";
        }

        if (empty($_POST["password"])) {
            $signup_errors["password"] = "Password is required.";
        }

        if (($_POST["password"] ?? "") !== ($_POST["password_confirmation"] ?? "")) {
            $signup_errors["password_confirmation"] = "Passwords do not match.";
        }

        // If no validation errors, continue with signup
        if (empty($signup_errors)) {

            // Check if email already exists
            $check = $mysqli->prepare("SELECT id FROM account WHERE email = ?");
            $check->bind_param("s", $_POST["email"]);
            $check->execute();
            $exists = $check->get_result()->fetch_assoc();

            if ($exists) {
                $signup_errors["email"] = "An account with that email already exists.";

            } else {

                // Insert new account
                $sql = "INSERT INTO account (name, email, password_hash) VALUES (?, ?, ?)";
                $stmt = $mysqli->prepare($sql);

                // Hash password securely
                $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

                $stmt->bind_param("sss", $_POST["name"], $_POST["email"], $password_hash);
                $stmt->execute();

                // Redirect to login page with success message
                header("Location: login.php?signup=success");
                exit;
            }
        }

    } else {

        // Validate login fields
        if (empty($_POST["email"])) {
            $login_errors["email"] = "Email is required.";
        }

        if (empty($_POST["password"])) {
            $login_errors["password"] = "Password is required.";
        }

        // If no validation errors, attempt login
        if (empty($login_errors)) {

            // Fetch account by email
            $sql = "SELECT * FROM account WHERE email = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $_POST["email"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $account = $result->fetch_assoc();

            // Verify password
            if ($account && password_verify($_POST["password"], $account["password_hash"])) {

                // Regenerate session ID for security
                session_regenerate_id(true);

                // Store user ID in session
                $_SESSION["account_id"] = $account["id"];
                $_SESSION["account_name"] = $account["name"];
                $_SESSION["account_email"] = $account["email"];

                // Redirect to homepage
                header("Location: " . APP_URL . "/index.php");
                exit;
            }

            // If login fails
            $login_errors["password"] = "Invalid login credentials.";
        }
    }
}

// Determine if signup panel should be active
$container_class = (!empty($signup_errors) || isset($_GET["signup"])) ? "active" : "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/login.css">
    <title>Log In</title>
</head>

<body>

<?php require APP_ROOT . '/Frontend/includes/nav.php'; ?>

<div class="container <?= $container_class ?>" id="container">

    <!-- SIGN UP -->
    <div class="form-container sign-up">
        <form method="POST">
            <h1>Create Account</h1>

            <?php if (isset($_GET["signup"]) && $_GET["signup"] === "success"): ?>
                <p class="success-message">Account created successfully! You may now log in.</p>
            <?php endif; ?>

            <input type="hidden" name="signup" value="1">

            <input type="text" name="name" placeholder="Name" value="<?= htmlspecialchars($_POST["name"] ?? "") ?>">
            <?php if (!empty($signup_errors["name"])): ?>
                <p class="error"><?= $signup_errors["name"] ?></p>
            <?php endif; ?>

            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
            <?php if (!empty($signup_errors["email"])): ?>
                <p class="error"><?= $signup_errors["email"] ?></p>
            <?php endif; ?>

            <input type="password" name="password" placeholder="Password">
            <?php if (!empty($signup_errors["password"])): ?>
                <p class="error"><?= $signup_errors["password"] ?></p>
            <?php endif; ?>

            <input type="password" name="password_confirmation" placeholder="Repeat Password">
            <?php if (!empty($signup_errors["password_confirmation"])): ?>
                <p class="error"><?= $signup_errors["password_confirmation"] ?></p>
            <?php endif; ?>

            <button type="submit">Sign Up</button>
        </form>
    </div>

    <!-- SIGN IN -->
    <div class="form-container sign-in">
        <form method="POST">
            <h1>Log In</h1>

            <input type="email" name="email" placeholder="Email">
            <?php if (!empty($login_errors["email"])): ?>
                <p class="error"><?= $login_errors["email"] ?></p>
            <?php endif; ?>

            <input type="password" name="password" placeholder="Password">
            <?php if (!empty($login_errors["password"])): ?>
                <p class="error"><?= $login_errors["password"] ?></p>
            <?php endif; ?>

            <button type="submit">Log In</button>
        </form>
    </div>

    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Welcome Back!</h1>
                <p>Log in to access expanded features</p>
                <button class="hidden" id="login">Log In</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Welcome!</h1>
                <p>Register an account to access expanded features</p>
                <button class="hidden" id="register">Sign Up</button>
            </div>
        </div>
    </div>

</div>

<a href="<?= APP_URL ?>/index.php" class="primary">Return to Main Page</a>

<script src="../../Backend/js/login.js"></script>

<?php require APP_ROOT . '/Frontend/includes/footer.php'; ?>

</body>
</html>

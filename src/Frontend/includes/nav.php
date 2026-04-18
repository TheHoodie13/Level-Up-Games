<?php
require_once __DIR__ . '/../../config/bootstrap.php';

//checks if user is logged in, if so pulls their details for use in nav
$user = null;

//check if account_id is set in session
if (isset($_SESSION["account_id"])) {
    //connect to database
    $mysqli = require DB_PATH;
    //pull account details for logged in user
    $account_id = (int) $_SESSION["account_id"];
    //fetch account details
    $stmt = $mysqli->prepare("SELECT * FROM account WHERE id = ?");
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
}
?>

<!-- Correct CSS paths -->
<link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/includes.css">
<link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/main.css">
    <link rel="icon" type="image" href="<?= APP_URL ?>/assets\img\LvlUpGames-Logo.png">
<nav class="LvlUp-nav" aria-label="Primary Navigation">
    <div class="LvlUp-nav__bar">

        <!-- Logo -->
        <a class="logo" href="<?= APP_URL ?>/index.php" aria-label="LevelUp Games Home">
            <img src="<?= APP_URL ?>/assets/img/LvlUpGames-Logo.png" alt="LevelUp Games Logo">
        </a>

        <!-- Search Box -->
        <form class="search" role="search" action="<?= APP_URL ?>/search.php" method="get">
            <input 
                name="q"
                type="search"
                placeholder="Search for products"
                aria-label="Search for products"
                
            />
            <button type="submit">Search</button>
        </form>

        <!-- Primary Nav-Bar -->
        <ul class="quick-links">
            <li>
                <a href="<?= APP_URL ?>/Backend/product_handling/basket/items-basket.php" aria-label="View cart">
                    <img src="<?= APP_URL ?>/assets/img/shopping-cart.png" alt="Go to basket">
                    Go To Basket
                </a>
            </li>

            <?php if ($user): ?>
                <li>
                    <a href="<?= APP_URL ?>/Backend/account/account-details.php" aria-label="Account">
                        <img src="<?= APP_URL ?>/assets/img/account.png" alt="Account">
                        Hello, <?= htmlspecialchars($user["name"]) ?>
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="<?= APP_URL ?>/Backend/account/login.php" aria-label="Sign In">
                        <img src="<?= APP_URL ?>/assets/img/log-in.png" alt="Log In/Create Account">
                        Log In/Create Account
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<?php
// Load full environment (APP_ROOT, APP_URL, DB_PATH, autoloader, config)
require_once __DIR__ . '/../../config/bootstrap.php';
?>

<link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/includes.css">

<footer class="LvlUp-footer">
    <div class="cols">

        <!-- Left block with logo -->
        <div>
            <h4>Level-Up Games</h4>
            <p>Quality gaming products for enthusiasts, tested and reccommended by staff.</p>
        </div>

        <div>
            <h4>Help</h4>
            <ul>
                <li><a href="<?= APP_URL ?>/Backend/customer_support/support.php">Customer Support</a></li>
            </ul>
        </div>

        <div>
            <h4>Company</h4>
            <ul>
                <li><a href="<?= APP_URL ?>/Frontend/includes/about.html">About</a></li>
                <li><a href="<?= APP_URL ?>/Frontend/includes/privacy.html">Privacy</a></li>
            </ul>
        </div>

    </div>

    <!-- Website Copyright -->
    <p class="legal">
        © <?= date('Y') ?> Lvl-Up Games! Ltd — All rights reserved
        all trademarks are property of their respective owners and are used under license/fair use.
    </p>
</footer>
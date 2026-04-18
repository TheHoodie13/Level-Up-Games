<?php
session_start();

// Correct bootstrap path
require_once __DIR__ . '/../../../bootstrap.php';

// DB connection
$mysqli = require DB_PATH;

// Redirect if not logged in
if (!isset($_SESSION["account_id"])) {
    header("Location: " . APP_URL . "/Backend/signup_page/signin.php");
    exit;
}

$userId = (int) $_SESSION["account_id"];

// Fetch basket items
$sql = "
    SELECT
        b.id AS basket_id,
        b.quantity,
        p.id AS product_id,
        p.title,
        p.rrp_price,
        p.discount,
        p.description,
        p.image
    FROM basket b
    JOIN products p ON b.product_id = p.id
    WHERE b.account_id = ?
    ORDER BY b.added_at DESC
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$basketItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate totals
$basketTotal = 0.0;

foreach ($basketItems as &$row) {
    $rrp = (float) $row["rrp_price"];
    $discount = (int) $row["discount"];

    $unitPrice = $discount > 0 ? $rrp * (1 - $discount / 100) : $rrp;
    $lineTotal = $unitPrice * (int) $row["quantity"];

    $basketTotal += $lineTotal;

    $row["unit_price"] = $unitPrice;
    $row["line_total"] = $lineTotal;
}
unset($row);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Basket - Level-up Games</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/main.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/product.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/basket.css">
</head>
<body>

<?php require APP_ROOT . "/Frontend/includes/nav.php"; ?>

<main class="catalog">
    <div class="section-head">
        <h1>Your Basket</h1>
    </div>

    <?php if (empty($basketItems)): ?>

        <p>Your basket is empty.</p>
        <p><a class="button ghost" href="<?= APP_URL ?>/search.php">Continue Shopping</a></p>

    <?php else: ?>

        <ul class="basket-list">
            <?php foreach ($basketItems as $row): ?>

                <li class="basket-item">

                    <!-- IMAGE -->
                    <div>
                        <?php
                            $imgFile = $row["image"] ?? null;
                            $imgPath = $imgFile
                                ? APP_URL . "/" . ltrim($imgFile, '/')
                                : APP_URL . "/assets/items/placeholder.png";
                        ?>
                        <img src="<?= htmlspecialchars($imgPath) ?>"
                             alt="<?= htmlspecialchars($row["title"]) ?>">
                    </div>

                    <!-- DETAILS -->
                    <div class="basket-details">
                        <strong><?= htmlspecialchars($row["title"]) ?></strong>
                        <span>
                            Qty: <?= (int) $row["quantity"] ?>
                            • Unit: £<?= number_format($row["unit_price"], 2) ?>
                            • Line total:
                            <strong>£<?= number_format($row["line_total"], 2) ?></strong>
                        </span>
                    </div>

                    <!-- ACTIONS -->
                    <div class="basket-actions">
                        <a class="button small ghost"
                           href="<?= APP_URL ?>/Backend/product_handling/item.php?id=<?= (int) $row["product_id"] ?>">
                            View
                        </a>

                        <form method="post"
                              action="<?= APP_URL ?>/Backend/product_handling/basket/basket-remove.php">
                            <input type="hidden" name="basket_id" value="<?= (int) $row["basket_id"] ?>">
                            <button type="submit" class="button small danger">Remove</button>
                        </form>
                    </div>

                </li>

            <?php endforeach; ?>
        </ul>

        <!-- SUMMARY -->
        <div class="basket-summary">
            <div>
                <strong>Total: £<?= number_format($basketTotal, 2) ?></strong>
            </div>

            <div class="basket-actions">
                <a href="<?= APP_URL ?>/search.php" class="button ghost">Continue Shopping</a>

                <form action="#" method="post">
                    <button type="submit" class="button primary">Checkout</button>
                </form>
            </div>
        </div>

    <?php endif; ?>
</main>

<?php require APP_ROOT . "/Frontend/includes/footer.php"; ?>

</body>
</html>
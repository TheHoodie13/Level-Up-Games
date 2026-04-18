<?php

// Load environment (APP_ROOT, APP_URL, DB_PATH, autoloader, config)
require_once __DIR__ . '/../../config/bootstrap.php';

// Database connection
$mysqli = require DB_PATH;

// Validate item ID
$itemId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$itemId) {
    http_response_code(404);
    echo "Item not found.";
    exit;
}

//Pull item details from database
//includes filter name from filters
$sql = "
    SELECT 
        p.id,
        p.title,
        p.description,
        p.rrp_price,
        p.discount,
        p.image,
        p.release_date,
        f.name AS type_name
    FROM products p
    JOIN filters f ON p.product_filter_id = f.id
    WHERE p.id = ?
    LIMIT 1
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $itemId);
$stmt->execute();

$item = $stmt->get_result()->fetch_assoc();

if (!$item) {
    http_response_code(404);
    echo "Item not found.";
    exit;
}

// Extract fields
$title       = $item["title"];
$typeName    = $item["type_name"];
$description = $item["description"];
$rrp         = (float)$item["rrp_price"];
$discount    = (int)$item["discount"];

// Image URL (portable)
$image = !empty($item["image"])
    ? APP_URL . "/" . ltrim($item["image"], '/')
    : APP_URL . "/assets/items/placeholder.png";

// Price logic
$finalPrice = $discount > 0
    ? $rrp * (1 - $discount / 100)
    : $rrp;

// Release logic
$releaseDateRaw = $item["release_date"];
$releaseDate    = $releaseDateRaw ? new DateTime($releaseDateRaw) : null;
$today          = new DateTime("today");
$isPreorder     = $releaseDate && $releaseDate > $today;

// Badge
$badgeText = $typeName;
if ($discount > 0) {
    $badgeText .= " | {$discount}% off";
}

$pageTitle = "Level-up Games - " . $title;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS (portable URLs) -->
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/main.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/product.css">
</head>
<body>

<!-- Navigation -->
<?php require APP_ROOT . "/Frontend/includes/nav.php"; ?>

<main class="item-page">

<section class="item-layout">

    <!-- LEFT COLUMN -->
    <div class="item-left">

        <div class="item-media">
            <img 
                src="<?= htmlspecialchars($image) ?>" 
                alt="<?= htmlspecialchars($title) ?> cover art"
            >
            <span class="badge item-badge">
                <?= htmlspecialchars($badgeText) ?>
            </span>
        </div>

        <h1 class="item-title"><?= htmlspecialchars($title) ?></h1>

        <?php if ($isPreorder): ?>
            <p class="item-release item-release--preorder">
                Coming <?= $releaseDate->format("j M Y") ?> — Pre‑order now
            </p>
        <?php elseif ($releaseDate): ?>
            <p class="item-release">
                Released <?= $releaseDate->format("j M Y") ?>
            </p>
        <?php endif; ?>

        <div class="item-price-block">
            <span class="item-price-current">
                £<?= number_format($finalPrice, 2) ?>
            </span>

            <?php if ($discount > 0): ?>
                <span class="item-price-rrp">£<?= number_format($rrp, 2) ?></span>
                <span class="item-price-discount">-<?= $discount ?>%</span>
            <?php endif; ?>
        </div>

        <div class="item-cta">
            <form method="post" action="<?= APP_URL ?>/Backend/product_handling/basket/basket-add.php">
                <input type="hidden" name="item_id" value="<?= (int)$itemId ?>">
                <button class="button primary large">
                    <?= $isPreorder ? "Pre‑Order" : "Add to Basket" ?>
                </button>
            </form>
        </div>

    </div>

    <!-- RIGHT COLUMN -->
    <div class="item-right">

        <div class="item-description">
            <h2>About this item</h2>
            <p><?= nl2br(htmlspecialchars($description)) ?></p>
        </div>

        <div class="item-meta">
            <h3>Product Details</h3>
            <ul>
                <li><strong>Platform / Type:</strong> <?= htmlspecialchars($typeName) ?></li>
            </ul>
        </div>

    </div>

</section>

</main>

<!-- Footer -->
<?php require APP_ROOT . "/Frontend/includes/footer.php"; ?>

</body>
</html>
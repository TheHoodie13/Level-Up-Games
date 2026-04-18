<?php

//bootstrap is needed to Load config
//config creates relative filepath variables to link together the frontend and backend
require_once __DIR__ . '/config/bootstrap.php';

//database connection
$mysqli = require DB_PATH;

//set inputs for query
$q         = trim($_GET["q"] ?? "");
$minPrice  = $_GET["min_price"] ?? "";
$maxPrice  = $_GET["max_price"] ?? "";
$sort      = $_GET["sort"] ?? "relevance";

// define fields for sorting
$allowedSorts = ["relevance", "price_asc", "price_desc", "release_newest"];
if (!in_array($sort, $allowedSorts, true)) {
    $sort = "relevance";
}

// Build WHERE conditions
// forces each entered statement to be formatted correctly 
// by defining the parameter types, we prevent SQL injection
$conditions = []; 
$params     = [];
$types      = "";

if ($q !== "") {
    $conditions[] = "LOWER(p.title) LIKE ?"; //forces the search to be lowercase
    $params[]     = "%" . strtolower($q) . "%"; //adds 'like' operators to the search term to allow for partial matches
    $types       .= "s"; // sets as 's' for a string parameter
}
// checks if the minimum price is set and is a valid number
// applies the discount to the RRP price to get the final price for comparison
if ($minPrice !== "" && is_numeric($minPrice)) {
    $conditions[] = "(p.rrp_price * (100 - p.discount) / 100) >= ?";
    $params[]     = (float)$minPrice; 
    $types       .= "d"; // sets as 'd' for a double parameter
}
// checks if the maximum price is set and is a valid number
// applies the discount to the RRP price to get the final price for comparison
if ($maxPrice !== "" && is_numeric($maxPrice)) {
    $conditions[] = "(p.rrp_price * (100 - p.discount) / 100) <= ?";
    $params[]     = (float)$maxPrice;
    $types       .= "d"; // sets as 'd' for a double parameter again
}
// whereSQL 
// if there are conditions, we join them with 'AND' and prepend 'WHERE'
$whereSql = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";

// Determine ORDER BY clause based on sorting option
// using a case statement so user input is not directly used in the SQL, preventing SQL injection
switch ($sort) {
    case "price_asc":
        $orderSql = "final_price ASC";
        break;

    case "price_desc":
        $orderSql = "final_price DESC";
        break;

    case "release_newest":
        $orderSql = "p.release_date DESC, p.title ASC";
        break;

    default:
        $orderSql = "p.title ASC";
        break;
}

// Dynamic SQL query
$sql = "
    SELECT
        p.*,
        f.name AS type_name,
        (p.rrp_price * (100 - p.discount) / 100) AS final_price
    FROM products p
    JOIN filters f ON p.product_filter_id = f.id
    $whereSql
    ORDER BY $orderSql
";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die('SQL error: ' . $mysqli->error . "<br><br>SQL: " . $sql);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Page meta
$pageTitle       = "Level-Up Games - Search";
$pageDescription = "Search for Games, DLC and other gaming products at Level-Up Games.";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>" />
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <!-- Portable CSS paths -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css" />
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/main.css" />
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/index.css" />
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/product.css" />
</head>
<body>

<?php require APP_ROOT . "/Frontend/includes/nav.php"; ?>

<main class="catalog">
    <h1>Search results</h1>

    <?php if ($q !== ""): ?>
        <p>Showing results for “<?= htmlspecialchars($q) ?>”</p>
    <?php endif; ?>

    <!-- Filter Bar -->
    <form method="get" class="filter-bar"
          style="margin:1rem 0; display:flex; flex-wrap:wrap; gap:1rem; align-items:flex-end;">
        <input type="hidden" name="q" value="<?= htmlspecialchars($q) ?>">

        <label>
            Min price (£)
            <input type="number" step="0.01" name="min_price"
                   value="<?= htmlspecialchars($minPrice) ?>">
        </label>

        <label>
            Max price (£)
            <input type="number" step="0.01" name="max_price"
                   value="<?= htmlspecialchars($maxPrice) ?>">
        </label>

        <label>
            Sort by
            <select name="sort">
                <option value="relevance" <?= $sort === "relevance" ? "selected" : "" ?>>Relevance</option>
                <option value="price_asc" <?= $sort === "price_asc" ? "selected" : "" ?>>Price: low to high</option>
                <option value="price_desc" <?= $sort === "price_desc" ? "selected" : "" ?>>Price: high to low</option>
                <option value="release_newest" <?= $sort === "release_newest" ? "selected" : "" ?>>Release date: newest</option>
            </select>
        </label>

        <button type="submit" class="button primary">Apply</button>
    </form>

    <?php if (empty($items)): ?>
        <p>No items matched your search. Try adjusting your filters.</p>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($items as $p): ?>
                <?php
                    $badge = $p["type_name"];
                    if ((int)$p["discount"] > 0) {
                        $badge .= " | " . (int)$p["discount"] . "% off";
                    }

                    $price = (float)$p["final_price"];

                    $isPreorder = !empty($p["release_date"]) &&
                                  $p["release_date"] > date("Y-m-d");

                    $buttonText = $isPreorder ? "Pre-order" : "Buy";

                    // Correct portable image path
                    $image = !empty($p["image"])
                        ? APP_URL . "/" . ltrim($p["image"], '/')
                        : APP_URL . "/assets/items/placeholder.png";
                ?>
                <article class="card">
                    <div class="card__media">
                        <img src="<?= htmlspecialchars($image) ?>"
                             alt="<?= htmlspecialchars($p['title']) ?> image" />
                        <span class="badge"><?= htmlspecialchars($badge) ?></span>
                    </div>

                    <div class="card__body">
                        <h3 class="card__title"><?= htmlspecialchars($p['title']) ?></h3>
                        <p class="price">£<?= number_format($price, 2) ?></p>

                        <div class="card__actions">
                            <form method="post" action="<?= APP_URL ?>/Backend/product_handling/basket/basket-add.php" style="margin: 0;">
                                <input type="hidden" name="item_id" value="<?= (int)$p['id'] ?>">
                                <button class="button small"><?= htmlspecialchars($buttonText) ?></button>
                            </form>

                            <a class="button small ghost"
                               href="<?= APP_URL ?>/Backend/product_handling/item.php?id=<?= (int)$p['id'] ?>">
                                Details
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require APP_ROOT . "/Frontend/includes/footer.php"; ?>

</body>
</html>
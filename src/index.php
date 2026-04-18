<?php
// Load config + constants + paths
require_once __DIR__ . '/config/bootstrap.php';
// Load database connection
$mysqli = require DB_PATH;

$pageTitle = 'Level-Up Games';
$pageDescription = 'Level up your gaming experience!';
$filter = $_GET['filter'] ?? null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>"/>
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/main.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/index.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/buttons.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/Frontend/css/product.css">
    <script src="<?= APP_URL ?>/Backend/js/carousel.js" defer></script>
</head>

<body>

<?php require APP_ROOT . '/Frontend/includes/nav.php'; ?>

<!-- HERO -->
<header class="hero hero--xl hero--mint">
    <div class="hero_inner hero--center">
        <h1 class="brand brand--xl">
            <a href="<?= APP_URL ?>/index.php">Level‑up! <span>Games</span></a>
        </h1>

        <h1 class="tag tag--lg">Your space to Level-Up! your gaming experience.</h1>
        <h2>
            <strong>      
        From Nintendo to PlayStation, Xbox, and PC games. Influenced by the players that make gaming happen.
            </strong>
        </h2>
        <p class="sub sub--lg">
        All your Games, DLC, and Accessories in one place, modern marketplace by gamers, for gamers.
        Everything gaming, all in one place, You’ll find new releases, best sellers, 
        and games from all the major publishers, all sorts of games.
        powered by a gaming community that gets stronger with every new player that joins.
        </p>

        <div class="hero_cta hero_cta--center">
            <a class="filter-btn" href="#best-sellers">Shop Best Sellers</a>
            <a class="filter-btn" href="#new-releases">Explore New Releases</a>
        </div>
    </div>
</header>

<!-- NEW RELEASES -->
 <div class="seperator-bar">
<section id="new-releases" class="catalog section--xl section--mint">
    <div class="section-head section-head--center">
        <h2 class="section-title">New Releases</h2>
    </div>
    <p class="section-sub">Fresh drops land weekly. Stay ahead of the meta.</p>

    <section class="carousel carousel--wide section--xl" aria-label="New Releases">
        <div class="carousel__viewport" id="carousel">
        <?php
        $today = date("Y-m-d");

        $sqlCarousel = "
            SELECT 
                i.id,
                i.title,
                i.image,
                i.release_date
            FROM products i
            WHERE i.release_date <= ?
            ORDER BY i.release_date DESC
            LIMIT 5
        ";

        $stmtCarousel = $mysqli->prepare($sqlCarousel);
        $stmtCarousel->bind_param("s", $today);
        $stmtCarousel->execute();
        $carouselItems = $stmtCarousel->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($carouselItems as $index => $carouselItem):
            $img = $carouselItem["image"] ?: APP_URL . "/assets/items/placeholder.png";
            $activeSlide = ($index === 0) ? "is-active" : "";
            $displayGame = APP_URL . "/Backend/product_handling/item.php?id=" . (int)$carouselItem['id'];
        ?>
            <div class="slide <?= $activeSlide ?>" data-href="<?= htmlspecialchars($displayGame) ?>">
                <img src="<?= htmlspecialchars($img) ?>" 
                    alt="<?= htmlspecialchars($carouselItem['title']) ?>">
            </div>
        <?php endforeach; ?>
        </div>

        <button class="carousel__btn prev">&#8249;</button>
        <button class="carousel__btn next">&#8250;</button>

        <div class="carousel__dots" id="carousel-dots"></div>
    </section>
</section>
</div>
<main>

<!-- BEST SELLERS -->
 
<section id="best-sellers" class="product-info section--xl">
   
    <div class="section-head section-head--center">
        <h2 class="section-title section-title--xl">Best Sellers</h2>
    </div>
    
    <p class="section-sub">Some of the most popular products amongst our consumers!.</p>

    <?php $active = $_GET['filter'] ?? ''; ?>

    <!-- FILTER BAR -->
    <div class="seperator-bar">
        <a href="<?= APP_URL ?>/" class="filter-btn <?= $active === '' ? 'active' : '' ?>">View All</a>
        <a href="?filter=<?= urlencode('PC') ?>" class="filter-btn <?= $active === 'PC' ? 'active' : '' ?>">PC</a>
        <a href="?filter=<?= urlencode('PS') ?>" class="filter-btn <?= $active === 'PS' ? 'active' : '' ?>">PlayStation</a>
        <a href="?filter=<?= urlencode('Xbox') ?>" class="filter-btn <?= $active === 'Xbox' ? 'active' : '' ?>">Xbox</a>
        <a href="?filter=<?= urlencode('Nintendo') ?>" class="filter-btn <?= $active === 'Nintendo' ? 'active' : '' ?>">Nintendo</a>
        <a href="?filter=DLC" class="filter-btn <?= $active === 'DLC' ? 'active' : '' ?>">DLC</a>
        <a href="?filter=OTHER" class="filter-btn <?= $active === 'OTHER' ? 'active' : '' ?>">OTHERS</a>


    <?php
$active = $_GET['filter'] ?? '';

$sqlBest = "
    SELECT
        p.id,
        p.title,
        p.rrp_price,
        p.discount,
        p.image,
        p.release_date,
        f.name AS type_name,
        COALESCE(SUM(b.quantity), 0) AS popularity
    FROM products p
    JOIN filters f ON p.product_filter_id = f.id
    LEFT JOIN basket b ON b.product_id = p.id
";

// WHERE clause only when a filter is selected
$params = [];
$types = "";

if ($active !== '') {
    $sqlBest .= " WHERE f.name = ? ";
    $params[] = $active;
    $types .= "s";
}

$sqlBest .= "
    GROUP BY p.id
    ORDER BY popularity DESC, p.release_date DESC
";

// LIMIT only when NOT View All
if ($active !== '') {
    $sqlBest .= " LIMIT 10";
}

$stmt = $mysqli->prepare($sqlBest);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

    if (count($products) === 0) {
        $sqlFallback = "
            SELECT
                p.id,
                p.title,
                p.rrp_price,
                p.discount,
                p.image,
                p.release_date,
                f.name AS type_name
            FROM products p
            JOIN filters f ON p.product_filter_id = f.id
            ORDER BY p.release_date DESC
            LIMIT 10
        ";

        $resultFallback = $mysqli->query($sqlFallback);
        if ($resultFallback) {
            $products = $resultFallback->fetch_all(MYSQLI_ASSOC);
        }
    }

    $today = date("Y-m-d");
    ?>

    <!-- ROW / ROWS LAYOUT -->
    <div class="grid grid--rows">
    <?php foreach ($products as $p): ?>
    <?php
        $rrp = (float)$p["rrp_price"];
        $discount = (int)$p["discount"];
        $price = $discount > 0 ? $rrp * (1 - $discount / 100) : $rrp;
        $isPreorder = !empty($p["release_date"]) && $p["release_date"] > $today;

        $badge = $discount > 0
            ? "{$p['type_name']} • {$discount}% OFF"
            : $p['type_name'];

        $img = !empty($p["image"]) 
            ? $p["image"] 
            : APP_URL . "/assets/items/placeholder.png";
    ?>

    <article class="card">

        <div class="card__media">
            <img src="<?= htmlspecialchars($img) ?>" 
                 alt="<?= htmlspecialchars($p['title']) ?> image">
                 <span class="badge"><?= htmlspecialchars($badge) ?></span>
            </div>

        <span class="card__body">
            <h3 class="card__title"><?= htmlspecialchars($p['title']) ?></h3>
            <p class="price">£<?= number_format($price, 2) ?></p>

            <div class="card__actions">
                <form method="post" action="<?= APP_URL ?>/Backend/product_handling/basket/basket-add.php">
                    <input type="hidden" name="item_id" value="<?= (int)$p['id'] ?>">
                    <button class="button small primary" type="submit">
                        <?= $isPreorder ? "Pre‑order" : "Add" ?>
                    </button>
                </form>

                <a class="button small ghost"  
                   href="<?= APP_URL ?>/Backend/product_handling/item.php?id=<?= (int)$p['id'] ?>">Detail</a>
            </div>
        </span>

    </article>

    <?php endforeach; ?>
    </div>
    </div>
</section>

</main>

<?php require APP_ROOT . '/Frontend/includes/footer.php'; ?>

</body>
</html>
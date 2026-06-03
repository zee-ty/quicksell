<?php
require 'db.php';

// fetch active listings
$stmt = $pdo->query("
    SELECT l.*, u.first_name, c.name AS category
    FROM listings l
    JOIN users u ON u.user_id = l.seller_id
    JOIN categories c ON c.category_id = l.category_id
    WHERE l.status = 'active'
    ORDER BY l.created_at DESC
");
$listings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickSell - Buy & Sell C2C</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">QuickSell</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="sell.php">Sell</a></li>
                <?php if (is_logged_in()): ?>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout (<?= htmlspecialchars($_SESSION['first_name']) ?>)</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<header class="hero text-center py-5 bg-light">
    <div class="container">
        <h1>Buy & Sell with people near you</h1>
        <p class="lead">QuickSell connects buyers and sellers directly.</p>
        <input type="text" id="searchBox" class="form-control w-50 mx-auto" placeholder="Search listings...">
    </div>
</header>

<main class="container py-4">
    <h2 class="mb-4">Latest Listings</h2>
    <div class="row" id="listings">
        <?php foreach ($listings as $l): ?>
            <div class="col-md-4 col-sm-6 mb-4 listing-card">
                <div class="card h-100">
                    <?php $img = !empty($l['image']) ? 'uploads/' . rawurlencode($l['image']) : 'assets/img/placeholder.jpg'; ?>
                    <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" alt="<?= htmlspecialchars($l['title']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($l['title']) ?></h5>
                        <p class="card-text text-muted small"><?= htmlspecialchars($l['category']) ?> • <?= htmlspecialchars($l['location']) ?></p>
                        <p class="h5 text-primary">R <?= number_format($l['price'], 2) ?></p>
                        <a href="listing.php?id=<?= $l['listing_id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<footer class="bg-dark text-light py-3 text-center">
    <small>&copy; 2026 QuickSell. A student project.</small>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/main.js"></script>
</body>
</html>


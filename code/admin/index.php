<?php
require '../db.php';
require_login();

// RBAC: only admin roles allowed
if (!in_array($_SESSION['role'] ?? '', ['super_admin','admin','moderator'])) {
    die("Access denied. Admins only.");
}

$counts = [
    'users'    => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'listings' => $pdo->query("SELECT COUNT(*) FROM listings WHERE status='active'")->fetchColumn(),
    'orders'   => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TradeMate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">TradeMate Admin</a>
        <div>
            <span class="text-light me-3">Role: <?= htmlspecialchars($_SESSION['role']) ?></span>
            <a href="../logout.php" class="btn btn-sm btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-4"><div class="card p-3"><h6>Total Users</h6><h2><?= $counts['users'] ?></h2></div></div>
        <div class="col-md-4"><div class="card p-3"><h6>Active Listings</h6><h2><?= $counts['listings'] ?></h2></div></div>
        <div class="col-md-4"><div class="card p-3"><h6>Orders</h6><h2><?= $counts['orders'] ?></h2></div></div>
    </div>

    <div class="card p-3">
        <h5>Admin Actions</h5>
        <ul>
            <li><a href="users.php">Manage Users (CRUD with RBAC)</a></li>
            <li><a href="listings.php">Manage Listings</a></li>
            <?php if ($_SESSION['role'] === 'super_admin'): ?>
                <li><a href="roles.php">Manage Roles (Super Admin only)</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
</body>
</html>

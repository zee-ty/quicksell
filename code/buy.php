<?php
require 'db.php';
require_login();

$listing_id = intval($_GET['id'] ?? 0);
if ($listing_id <= 0) {
    die('Invalid listing ID.');
}

$stmt = $pdo->prepare("SELECT l.*, u.first_name, u.last_name, u.email, u.phone FROM listings l JOIN users u ON u.user_id = l.seller_id WHERE l.listing_id = ? AND l.status = 'active'");
$stmt->execute([$listing_id]);
$listing = $stmt->fetch();

if (!$listing) {
    die('Listing not available for purchase.');
}

$buyer_id = intval($_SESSION['user_id']);
$buyerCheck = $pdo->prepare("SELECT user_id FROM users WHERE user_id = ?");
$buyerCheck->execute([$buyer_id]);
if (!$buyerCheck->fetchColumn()) {
    session_destroy();
    die('Your session is invalid. Please log in again.');
}

if ($listing['seller_id'] === $buyer_id) {
    die('You cannot buy your own listing.');
}

$insert = $pdo->prepare("INSERT INTO orders (listing_id, buyer_id, seller_id, amount, status) VALUES (?, ?, ?, ?, 'pending')");
$insert->execute([
    $listing_id,
    $buyer_id,
    $listing['seller_id'],
    $listing['price'],
]);

$order_id = $pdo->lastInsertId();
$pdo->prepare("UPDATE listings SET status = 'sold' WHERE listing_id = ?")->execute([$listing_id]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Submitted</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <div class="alert alert-success">
        <h1 class="h4">Purchase request submitted</h1>
        <p>Your order has been placed and is pending. The seller will be notified.</p>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($listing['title']) ?></h5>
            <p class="mb-1"><strong>Order ID:</strong> <?= htmlspecialchars($order_id) ?></p>
            <p class="mb-1"><strong>Seller:</strong> <?= htmlspecialchars($listing['first_name'] . ' ' . $listing['last_name']) ?></p>
            <p class="mb-1"><strong>Price:</strong> R <?= number_format($listing['price'], 2) ?></p>
            <p class="mb-1"><strong>Contact seller:</strong> <a href="mailto:<?= htmlspecialchars($listing['email']) ?>?subject=Purchase interest in <?= rawurlencode($listing['title']) ?>"><?= htmlspecialchars($listing['email']) ?></a></p>
        </div>
    </div>
    <a href="index.php" class="btn btn-primary">Return to Marketplace</a>
</div>
</body>
</html>

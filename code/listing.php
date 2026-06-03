<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT l.*, u.first_name, u.last_name, u.email, u.phone, c.name AS category
    FROM listings l
    JOIN users u ON u.user_id = l.seller_id
    JOIN categories c ON c.category_id = l.category_id
    WHERE listing_id = ?
");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) { die("Listing not found."); }

// -------- Handle in-site message --------
$messageSent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_logged_in() && !empty($_POST['message_body'])) {
    $body = trim($_POST['message_body']);
    if ($body !== '') {
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, listing_id, body)
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $item['seller_id'], $item['listing_id'], $body]);
        $messageSent = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($item['title']) ?> - Stoep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <a href="index.php" class="btn btn-link">&larr; Back</a>
    <div class="row mt-2">
        <?php $img = !empty($item['image']) ? 'uploads/' . rawurlencode($item['image']) : 'assets/img/placeholder.jpg'; ?>
        <div class="col-md-6"><img src="<?= htmlspecialchars($img) ?>" class="img-fluid" alt="<?= htmlspecialchars($item['title']) ?>"></div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($item['title']) ?></h2>
            <p class="text-muted"><?= htmlspecialchars($item['category']) ?> • <?= htmlspecialchars($item['location']) ?></p>
            <h3 class="text-primary">R <?= number_format($item['price'], 2) ?></h3>
            <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>
            <hr>
            <p><strong>Seller:</strong> <?= htmlspecialchars($item['first_name'] . ' ' . $item['last_name']) ?></p>

            <?php if (is_logged_in()): ?>
                <!-- Toggle the contact panel -->
                <button class="btn btn-success" type="button"
                        data-bs-toggle="collapse" data-bs-target="#contactPanel">
                    Contact Seller
                </button>
                <a href="buy.php?id=<?= $item['listing_id'] ?>" class="btn btn-primary">Buy Now</a>

                <!-- Collapsible contact panel -->
                <div class="collapse mt-3" id="contactPanel">
                    <div class="card card-body">
                        <h5>Seller Contact</h5>
                        <p class="mb-1">
                            <strong>Email:</strong>
                            <a href="mailto:<?= htmlspecialchars($item['email']) ?>?subject=Interested in <?= rawurlencode($item['title']) ?>">
                                <?= htmlspecialchars($item['email']) ?>
                            </a>
                        </p>
                        <?php if (!empty($item['phone'])): ?>
                            <p class="mb-1">
                                <strong>Phone:</strong>
                                <a href="tel:<?= htmlspecialchars($item['phone']) ?>">
                                    <?= htmlspecialchars($item['phone']) ?>
                                </a>
                            </p>
                        <?php endif; ?>

                        <hr>
                        <h6>Or send a message via Stoep</h6>
                        <?php if ($messageSent): ?>
                            <div class="alert alert-success py-1 px-2">Message sent to seller.</div>
                        <?php endif; ?>
                        <form method="POST">
                            <textarea name="message_body" class="form-control mb-2" rows="3"
                                      placeholder="Hi, is this item still available?" required></textarea>
                            <button class="btn btn-sm btn-primary" type="submit">Send Message</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <a class="btn btn-primary" href="login.php">Login to contact seller</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


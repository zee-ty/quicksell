<?php
require 'db.php';
require_login();

if (!in_array($_SESSION['role'] ?? '', ['seller','admin','super_admin'])) {
    die("You need a seller account to create listings.");
}

$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$msg  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']);
    $desc     = trim($_POST['description']);
    $price    = floatval($_POST['price']);
    $cat      = intval($_POST['category_id']);
    $cond     = $_POST['condition_type'];
    $location = trim($_POST['location']);

    $stmt = $pdo->prepare("INSERT INTO listings (seller_id, category_id, title, description, price, condition_type, location) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([$_SESSION['user_id'], $cat, $title, $desc, $price, $cond, $location]);
    $msg = "Listing created successfully!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell - TradeMate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4" style="max-width: 600px;">
    <h3>Create a Listing</h3>
    <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
    <form method="POST" class="card p-3">
        <div class="mb-2"><label>Title</label><input name="title" class="form-control" required></div>
        <div class="mb-2"><label>Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
        <div class="mb-2"><label>Price (R)</label><input type="number" step="0.01" name="price" class="form-control" required></div>
        <div class="mb-2"><label>Category</label>
            <select name="category_id" class="form-select">
                <?php foreach ($cats as $c): ?>
                    <option value="<?= $c['category_id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-2"><label>Condition</label>
            <select name="condition_type" class="form-select">
                <option value="new">New</option>
                <option value="used" selected>Used</option>
                <option value="refurbished">Refurbished</option>
            </select>
        </div>
        <div class="mb-3"><label>Location</label><input name="location" class="form-control"></div>
        <button class="btn btn-primary">Post Listing</button>
        <a href="index.php" class="btn btn-link">Cancel</a>
    </form>
</div>
</body>
</html>

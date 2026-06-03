<?php
// One-time QuickSell sample listing inserter.
// Run in browser and then delete this file.

$mysqli = new mysqli('localhost', 'root', '', 'quicksell');
if ($mysqli->connect_errno) {
    die('MySQL connect error: ' . $mysqli->connect_error);
}

$items = [
    [
        'seller_id' => 2,
        'category_id' => 4,
        'title' => 'Harry Potter Book Set',
        'description' => 'Complete Harry Potter paperback collection, great condition.',
        'price' => 499.99,
        'condition_type' => 'used',
        'location' => 'Pretoria',
        'image' => 'Harry Potter.jpg',
    ],
    [
        'seller_id' => 2,
        'category_id' => 3,
        'title' => 'Wooden Dining Table',
        'description' => 'Solid oak wooden table, seats 6 comfortably.',
        'price' => 2499.00,
        'condition_type' => 'used',
        'location' => 'Johannesburg',
        'image' => 'Wooden Table.jpg',
    ],
    [
        'seller_id' => 2,
        'category_id' => 5,
        'title' => 'Mountain Bike',
        'description' => 'Hardtail mountain bike with front suspension, perfect for trails.',
        'price' => 3200.00,
        'condition_type' => 'used',
        'location' => 'Cape Town',
        'image' => 'mountain bike.jpg',
    ],
    [
        'seller_id' => 2,
        'category_id' => 6,
        'title' => 'Toyota Corolla',
        'description' => 'Well maintained Toyota Corolla, reliable and fuel efficient.',
        'price' => 85000.00,
        'condition_type' => 'used',
        'location' => 'Durban',
        'image' => 'toyota corolla.avif',
    ],
];

$stmt = $mysqli->prepare('INSERT INTO listings (seller_id, category_id, title, description, price, condition_type, location, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, "active")');
if (!$stmt) {
    die('Prepare failed: ' . $mysqli->error);
}

foreach ($items as $item) {
    $stmt->bind_param(
        'iissdsss',
        $item['seller_id'],
        $item['category_id'],
        $item['title'],
        $item['description'],
        $item['price'],
        $item['condition_type'],
        $item['location'],
        $item['image']
    );
    if (!$stmt->execute()) {
        die('Insert failed: ' . $stmt->error);
    }
}

echo '<h1>Added QuickSell sample listings</h1>';
echo '<ul>';
foreach ($items as $item) {
    echo '<li>' . htmlspecialchars($item['title']) . ' (' . htmlspecialchars($item['image']) . ')</li>';
}
echo '</ul>';
echo '<p>Delete <code>code/add_quicksell_samples.php</code> after verifying the listings appear.</p>';

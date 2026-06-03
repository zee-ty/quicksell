<?php
// db.php - database connection (PDO)
// =======================================================
// LOCAL DEVELOPMENT (XAMPP/MAMP) - use these defaults
// =======================================================
$host = 'localhost';
$db   = 'quicksell';
$user = 'root';
$pass = '';

// =======================================================
// PRODUCTION (InfinityFree) - replace the four values
// above with the credentials shown in your InfinityFree
// MySQL Databases panel. Example:
//
// $host = 'sql300.infinityfree.com';
// $db   = 'epiz_12345678_trademate';
// $user = 'epiz_12345678';
// $pass = 'YourGeneratedPasswordHere';
// =======================================================

$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

session_start();

// helper: check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// helper: check role
function has_role($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

// helper: require login
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
?>

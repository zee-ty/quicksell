<?php
require 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name'] ?? '');
    $last  = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pwd   = $_POST['password'] ?? '';
    $role  = isset($_POST['as_seller']) ? 4 : 5; // 4=seller, 5=buyer

    if ($first && $last && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($pwd) >= 6) {
        $hash = password_hash($pwd, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role_id) VALUES (?,?,?,?,?)");
            $stmt->execute([$first, $last, $email, $hash, $role]);
            header('Location: login.php?registered=1');
            exit;
        } catch (PDOException $e) {
            $error = 'Email already exists.';
        }
    } else {
        $error = 'Please fill in all fields correctly. Password must be 6+ chars.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TradeMate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 480px;">
    <div class="card p-4">
        <h3 class="mb-3">Create your account</h3>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <div class="mb-2">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="as_seller" id="as_seller">
                <label class="form-check-label" for="as_seller">I want to sell items too</label>
            </div>
            <button class="btn btn-primary w-100" type="submit">Register</button>
        </form>
        <p class="mt-3 text-center"><a href="login.php">Already have an account? Login</a></p>
    </div>
</div>
</body>
</html>

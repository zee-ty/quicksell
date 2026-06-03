<?php
require 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pwd   = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT u.*, r.role_name FROM users u JOIN roles r ON r.role_id = u.role_id WHERE email = ? AND is_active = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pwd, $user['password_hash'])) {
        $_SESSION['user_id']    = $user['user_id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['role']       = $user['role_name'];

        // RBAC redirect: admins go to admin panel
        if (in_array($user['role_name'], ['super_admin','admin','moderator'])) {
            header('Location: admin/index.php');
        } else {
            header('Location: index.php');
        }
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TradeMate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 420px;">
    <div class="card p-4">
        <h3 class="mb-3">Sign in</h3>
        <?php if (isset($_GET['registered'])): ?>
            <div class="alert alert-success">Account created. Please log in.</div>
        <?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <div class="mb-2">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Login</button>
        </form>
        <p class="mt-3 text-center"><a href="register.php">Don't have an account? Register</a></p>
    </div>
</div>
</body>
</html>

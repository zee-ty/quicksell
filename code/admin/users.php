<?php
require '../db.php';
require_login();

// RBAC: admins and super_admins only
if (!in_array($_SESSION['role'] ?? '', ['super_admin','admin'])) {
    die("Access denied.");
}

// --- DELETE ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo->prepare("DELETE FROM users WHERE user_id = ?")->execute([$id]);
    header('Location: users.php');
    exit;
}

// --- UPDATE role ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $id   = intval($_POST['user_id']);
    $role = intval($_POST['role_id']);
    $pdo->prepare("UPDATE users SET role_id = ? WHERE user_id = ?")->execute([$role, $id]);
    header('Location: users.php');
    exit;
}

// --- CREATE new user ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role_id) VALUES (?,?,?,?,?)")
        ->execute([$_POST['first_name'], $_POST['last_name'], $_POST['email'], $hash, $_POST['role_id']]);
    header('Location: users.php');
    exit;
}

$users = $pdo->query("
    SELECT u.*, r.role_name
    FROM users u JOIN roles r ON r.role_id = u.role_id
    ORDER BY u.created_at DESC
")->fetchAll();
$roles = $pdo->query("SELECT * FROM roles")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - TradeMate Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">TradeMate Admin</a>
        <a href="../logout.php" class="btn btn-sm btn-outline-light">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <a href="index.php" class="btn btn-link">&larr; Dashboard</a>
    <h3 class="mb-3">Manage Users</h3>

    <!-- CREATE -->
    <div class="card p-3 mb-4">
        <h5>Add New User</h5>
        <form method="POST" class="row g-2">
            <div class="col-md-2"><input name="first_name" class="form-control" placeholder="First name" required></div>
            <div class="col-md-2"><input name="last_name" class="form-control" placeholder="Last name" required></div>
            <div class="col-md-3"><input name="email" type="email" class="form-control" placeholder="Email" required></div>
            <div class="col-md-2"><input name="password" type="password" class="form-control" placeholder="Password" required></div>
            <div class="col-md-2">
                <select name="role_id" class="form-select">
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['role_id'] ?>"><?= htmlspecialchars($r['role_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-1"><button name="create_user" class="btn btn-primary w-100">Add</button></div>
        </form>
    </div>

    <!-- READ + UPDATE + DELETE -->
    <div class="table-responsive">
        <table class="table table-bordered bg-white">
            <thead class="table-dark">
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['user_id'] ?></td>
                    <td><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <form method="POST" class="d-flex">
                            <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                            <select name="role_id" class="form-select form-select-sm me-1">
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['role_id'] ?>" <?= $r['role_id']==$u['role_id']?'selected':'' ?>>
                                        <?= htmlspecialchars($r['role_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button name="update_role" class="btn btn-sm btn-warning">Update</button>
                        </form>
                    </td>
                    <td>
                        <a href="?delete=<?= $u['user_id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

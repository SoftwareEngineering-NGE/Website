<?php require_once '../template/header.php'; ?>
<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../public/login.php');
    exit;
}

require '../lib/db.php';
require '../classes/User.php';

$db = new Database();
$pdo = $db->getConnection();
$userModel = new User($pdo);

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $userModel->delete($id);
    header("Location: manage_users.php");
    exit;
}

// Fetch all users
$users = $userModel->readAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../css/theme.css">
</head>
<body>
<div class="container">
    <h2>Manage Users</h2>
    <?php if (!empty($users)): ?>
        <table>
            <thead>
            <tr>
                <th>First Name</th>
                <th>Email</th>
                <th>Registered On</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= escape($user['first_name']) ?></td>
                    <td><?= escape($user['email']) ?></td>
                    <td><?= escape($user['created_at']) ?></td>
                    <td>
                        <a href="manage_users.php?delete=<?= $user['id'] ?>" onclick="return confirm('Delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</div>
</body>
</html>

<?php require_once '../template/header.php'; ?>

<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../public/login.php');
    exit;
}

require '../lib/db.php';
$db = new Database();
$pdo = $db->getConnection();

$stmt = $pdo->prepare("SELECT * FROM admin WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['admin_id']);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/theme.css">
</head>
<body>
<div class="container">
    <h1>Welcome Admin (<?= escape($admin['email']); ?>)</h1>

    <div class="links">
        <a href="view_adoption_requests.php" class="button">View Adoption Requests</a>
        <a href="manage_dogs.php" class="button">Manage Dogs</a>
        <a href="add_dog.php" class="button">Add New Dog</a>
        <a href="view_messages.php" class="button">View Messages</a>
        <a href="manage_users.php" class="button">Manage Users</a>
        <a href="../authentication/logout/logout.php" class="button">Log Out</a>
    </div>
</div>
</body>
</html>

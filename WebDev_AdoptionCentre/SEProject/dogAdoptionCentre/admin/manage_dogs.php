<?php require_once '../template/header.php'; ?>
<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../public/login.php');
    exit;
}

require '../lib/db.php';
require '../classes/Dog.php';

$db = new Database();
$pdo = $db->getConnection();
$dogModel = new Dog($pdo);

// Handle delete request
if (isset($_GET['delete'])) {
    $dogId = $_GET['delete'];
    if ($dogModel->delete($dogId)) {
        header("Location: manage_dogs.php");
        exit;
    } else {
        echo "Failed to delete dog.";
    }
}

// Get all dogs
$dogs = $dogModel->readAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Dogs</title>
    <link rel="stylesheet" href="../css/theme.css">
</head>
<body>
<div class="container">
    <h2>Manage Dogs</h2>
    <a href="add_dog.php" class="button">Add New Dog</a>
    <br><br>

    <?php if (count($dogs) > 0): ?>
        <table>
            <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Breed</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($dogs as $dog): ?>
                <tr>
                    <td>
                        <?php if (!empty($dog['image'])): ?>
                            <img src="<?= escape($dog['image']) ?>" width="80">
                        <?php else: ?>
                            No image
                        <?php endif; ?>
                    </td>
                    <td><?= escape($dog['name']) ?></td>
                    <td><?= escape($dog['breed']) ?></td>
                    <td><?= escape($dog['age']) ?></td>
                    <td><?= escape($dog['gender']) ?></td>
                    <td><?= escape($dog['description']) ?></td>
                    <td>
                        <a href="edit_dog.php?id=<?= $dog['id'] ?>" class="button">Edit</a>
                        <a href="manage_dogs.php?delete=<?= $dog['id'] ?>" class="button" onclick="return confirm('Are you sure you want to delete this dog?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No dogs found.</p>
    <?php endif; ?>
</div>
</body>
</html>

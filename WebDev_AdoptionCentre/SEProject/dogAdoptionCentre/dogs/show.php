<?php require_once '../template/header.php'; ?>

<?php
require '../lib/db.php';

$db = new Database();
$pdo = $db->getConnection();

$dogID = $_GET['id'] ?? null;

if (!$dogID) {
    echo "No dog selected.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM dogs WHERE id = :id");
$stmt->bindParam(':id', $dogID);
$stmt->execute();
$dog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dog) {
    echo "Dog not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($dog['name']); ?> - Details</title>
escape

</head>
<body>
<div class="container">
    <div class="house">
        <img src="<?php echo htmlspecialchars($dog['image']); ?>" alt="<?php echo htmlspecialchars($dog['name']); ?>">
        <h2><?php echo htmlspecialchars($dog['name']); ?></h2>
        <p><strong>Breed:</strong> <?php echo htmlspecialchars($dog['breed']); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($dog['age']); ?> years</p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($dog['gender']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($dog['description']); ?></p>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="request_form.php?id=<?php echo $dog['id']; ?>" class="button">Adopt Me</a>
        <?php else: ?>
            <a href="../public/login.php" class="button">Log in to adopt</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

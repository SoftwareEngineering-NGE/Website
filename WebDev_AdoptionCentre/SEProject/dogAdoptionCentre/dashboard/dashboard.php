<?php
require '../template/header.php';
require '../lib/db.php';

$db = new Database();
$pdo = $db->getConnection();

$userID = $_SESSION['user_id'];

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $userID);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch user's adoption requests
$stmt = $pdo->prepare("
    SELECT ar.*, d.name AS dog_name, d.breed 
    FROM adoption_requests ar
    LEFT JOIN dogs d ON ar.dog_id = d.id
    WHERE ar.user_id = :userID
    ORDER BY ar.request_date DESC
");
$stmt->bindParam(':userID', $userID);
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's favorites
$stmt = $pdo->prepare("
    SELECT d.* 
    FROM favorites f
    JOIN dogs d ON f.dog_id = d.id
    WHERE f.user_id = :userID
");
$stmt->bindParam(':userID', $userID);
$stmt->execute();
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard</title>
    <link rel="stylesheet" href="../css/theme.css">
</head>
<body>
<div class="dashboard-container">
    <h1>Welcome, <?php echo escape($user['first_name']); ?>!</h1>

    <h2>Your Adoption Requests</h2>
    <table>
        <thead>
        <tr>
            <th>Dog</th>
            <th>Breed</th>
            <th>Requested On</th>
            <th>Status</th>
            <th>Decision Date</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($requests): ?>
            <?php foreach ($requests as $req): ?>
                <tr>
                    <td><?php echo escape($req['dog_name']); ?></td>
                    <td><?php echo escape($req['breed']); ?></td>
                    <td><?php echo escape($req['request_date']); ?></td>
                    <td><?php echo escape($req['status']); ?></td>
                    <td><?php echo $req['decision_date'] ? escape($req['decision_date']) : 'N/A'; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">You haven't made any adoption requests yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <a href="../authentication/logout/logout.php" class="button">Log Out</a>
        <a href="../public/index.php" class="button back-button">Back to Home</a>
    </div>
</div>
</body>
</html>

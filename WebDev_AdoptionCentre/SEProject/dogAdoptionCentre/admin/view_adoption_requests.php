<?php require_once '../template/header.php'; ?>
<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../public/login.php');
    exit;
}

require '../lib/db.php';
require '../classes/AdoptionRequest.php';
require '../classes/Dog.php';

$db = new Database();
$pdo = $db->getConnection();
$requestModel = new AdoptionRequest($pdo);
$dogModel = new Dog($pdo);

// Handle Approve/Reject Actions
if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $request_id = intval($_GET['id']);
    $status = ($action === 'approve') ? 'Approved' : 'Rejected';

    // Update status
    $requestModel->updateStatus($request_id, $status);

    // If approved, update dog availability
    if ($status === 'Approved') {
        $request = $requestModel->readOne($request_id);
        if ($request) {
            $stmt = $pdo->prepare("UPDATE dogs SET is_available = FALSE WHERE id = :id");
            $stmt->execute(['id' => $request['dog_id']]);
        }
    }

    header('Location: view_adoption_requests.php');
    exit;
}

// Fetch all requests
$requests = $requestModel->readAllWithDetails();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adoption Requests</title>
    <link rel="stylesheet" href="../css/theme.css">
</head>
<body>
<div class="container">
    <h2>Adoption Requests</h2>
    <table>
        <thead>
        <tr>
            <th>User</th>
            <th>Email</th>
            <th>Dog</th>
            <th>Requested On</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($requests)): ?>
            <?php foreach ($requests as $req): ?>
                <tr>
                    <td><?= escape($req['first_name']) ?></td>
                    <td><?= escape($req['email']) ?></td>
                    <td><?= escape($req['dog_name'] ?? 'Dog Adopted') ?></td>
                    <td><?= escape($req['request_date']) ?></td>
                    <td><?= escape($req['status']) ?></td>
                    <td>
                        <?php if ($req['status'] === 'Pending'): ?>
                            <a href="?action=approve&id=<?= $req['id'] ?>">Approve</a> |
                            <a href="?action=reject&id=<?= $req['id'] ?>">Reject</a>
                        <?php else: ?>
                            <?= escape($req['status']) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">No adoption requests found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php require_once '../template/header.php'; ?>

<?php
require '../lib/db.php';
$db = new Database();
$pdo = $db->getConnection();

$dogID = $_GET['id'] ?? null;
if (!$dogID) {
    echo "<div class='container'><p>Invalid dog ID.</p></div>";
    require_once '../template/footer.php';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM dogs WHERE id = :id");
$stmt->bindParam(':id', $dogID);
$stmt->execute();
$dog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dog) {
    echo "<div class='container'><p>Dog not found.</p></div>";
    require_once '../template/footer.php';
    exit;
}

$userID = $_SESSION['user_id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message'] ?? '');
    $agreements = $_POST['agreements'] ?? [];

    if (empty($message)) {
        $errors[] = "Please provide a reason for adopting.";
    }

    if (count($agreements) < 3) {
        $errors[] = "You must agree to all terms.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO adoption_requests (user_id, dog_id, status, request_date) 
                               VALUES (:user_id, :dog_id, 'Pending', NOW())");
        $stmt->bindParam(':user_id', $userID);
        $stmt->bindParam(':dog_id', $dogID);
        $stmt->execute();

        // Redirect to dashboard
        header('Location: ../dashboard/dashboard.php');
        exit;
    }
}
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
    escape
        <title>Document</title>
    </head>
    <body>
    <div class="container">
        <div class="house">
            <h2>Adopt <?= htmlspecialchars($dog['name']) ?></h2>

            <?php if (!empty($errors)): ?>
                <div class="error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <p>Why do you want to adopt <strong><?= htmlspecialchars($dog['name']) ?></strong>?</p>
                <textarea name="message" rows="5" style="width:100%;" placeholder="Tell us why..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

                <p>Please confirm the following:</p>
                <label><input type="checkbox" name="agreements[]" value="care"> I agree to provide daily food and water.</label><br>
                <label><input type="checkbox" name="agreements[]" value="vet"> I agree to provide regular vet checkups and vaccinations.</label><br>
                <label><input type="checkbox" name="agreements[]" value="home"> I agree to provide a safe and loving home.</label><br>

                <br>
                <button type="submit" class="button">Yes, Send Request</button>
                <a href="/public/index.php" class="button">Cancel</a>
            </form>
        </div>
    </div>

    </body>
    </html>

<?php require_once '../template/footer.php'; ?>
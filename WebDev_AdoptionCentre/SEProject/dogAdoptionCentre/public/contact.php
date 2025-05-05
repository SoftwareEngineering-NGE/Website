<?php require_once '../template/header.php'; ?>

<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit;
}

require '../lib/db.php';
$db = new Database();
$pdo = $db->getConnection();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($subject) || empty($message)) {
        $errors[] = "Subject and message are required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (user_id, subject, message) VALUES (:user_id, :subject, :message)");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Admin</title>
        <link rel="stylesheet" href="/css/theme.css">
</head>
<body>
<div class="container form-container">
    <h2>Contact Admin</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo escape($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php elseif ($success): ?>
        <div class="success">Your message has been sent!</div>
    <?php endif; ?>

    <form method="POST">
        <label for="subject"><strong>Subject</strong></label>
        <input type="text" name="subject" id="subject" required value="<?php echo escape($_POST['subject'] ?? '') ?>">

        <label for="message"><strong>Message</strong></label>
        <textarea name="message" id="message" rows="6" required><?php echo escape($_POST['message'] ?? '') ?></textarea>

        <br><br>
        <button type="submit" class="button">Send Message</button>
    </form>
</div>
</body>
</html>

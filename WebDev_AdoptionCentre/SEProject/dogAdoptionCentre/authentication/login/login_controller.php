<?php
session_start();

require_once '../../lib/db.php';
require_once '../../classes/User.php';
require_once '../../classes/Admin.php';

$db = new Database();
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['psw'] ?? '';

    if (empty($email) || empty($password)) {
        echo "Email and password are required.";
        exit;
    }

    $user = new User($pdo);
    $admin = new Admin($pdo);

    // Try to authenticate user
    $userData = $user->authenticate($email, $password);
    if ($userData) {
        $_SESSION['Active'] = true;
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user_name'] = $userData['first_name'];

        header("Location: ../../dashboard/dashboard.php");
        exit;
    }

    // Try admin login
    $adminData = $admin->authenticate($email, $password);
    if ($adminData) {
        $_SESSION['Active'] = true;
        $_SESSION['admin_id'] = $adminData['id'];
        $_SESSION['admin_email'] = $adminData['email'];

        header("Location: ../../admin/admin_dashboard.php");
        exit;
    }

    echo "Invalid email or password.";
}

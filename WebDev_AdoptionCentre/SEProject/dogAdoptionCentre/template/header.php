<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function escape($string)
{
    return htmlspecialchars(trim(stripslashes($string)), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dog Adoption Centre</title>
    <link rel="stylesheet" href="/css/theme.css">
</head>
<body>

<header>
    <nav class="navbar">
        <a href="/public/index.php">
            <img src="/uploads/Logo3.png" alt="Dog Adoption Logo" class="navbar-logo">
        </a>
        <div class="navbar-links">
            <a href="/public/index.php">Home</a>
            <a href="/public/aboutus.php">About Us</a>
            <a href="/public/contact.php">Contact</a>

            <?php if (isset($_SESSION['admin_id'])): ?>
                <a href="/admin/admin_dashboard.php">Admin Dashboard</a>
                <a href="/admin/manage_dogs.php">Manage Dogs</a>
                <a href="/admin/view_adoption_requests.php">Requests</a>
                <a href="/admin/view_messages.php">Messages</a>
                <a href="/admin/manage_users.php">Users</a>
                <a href="/authentication/logout/logout.php">Log Out</a>

            <?php elseif (isset($_SESSION['user_id'])): ?>
                <a href="/dashboard/dashboard.php">My Dashboard</a>
                <a href="/authentication/logout/logout.php">Log Out</a>

            <?php else: ?>
                <a href="/public/login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

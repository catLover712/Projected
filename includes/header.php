<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>

    <meta charset="UTF-8">

    <title>Projected</title>

    <link rel="stylesheet" href="/Projected/assets/css/style.css?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Neucha&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=New+Tegomin&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bitcount&display=swap" rel="stylesheet">

</head>

<body>

<header class="header">

    <a href="/Projected/index.php" class="logo-link">
        <img src="/Projected/assets/images/Projected.png" alt="Logo" class="logo">
    </a>

    <img src="/Projected/assets/images/slogan.png" alt="slogan" class="slogan">

    <div class="auth-buttons">

        <?php if (isset($_SESSION['user_id'])): ?>

            <a href="/Projected/index.php?page=project&action=create" class="btn auth-btn">
                Create Project
            </a>

            <a href="/Projected/views/profile.php" class="btn auth-btn">
                Edit Profile
            </a>

            <a href="/Projected/controllers/UserController.php?logout=1" class="btn auth-btn">
                Logout
            </a>

        <?php else: ?>

            <a href="/Projected/views/login.php" class="btn auth-btn">
                Login
            </a>

            <a href="/Projected/views/register.php" class="btn auth-btn">
                Register
            </a>

        <?php endif; ?>

    </div>

</header>
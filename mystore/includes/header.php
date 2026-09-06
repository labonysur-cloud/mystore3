<?php
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Store</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header>
    <h1><a href="../index.php">My Store</a></h1>
    <nav>
        <ul class="nav-list">
            <li><a href="../pages/products.php">Products</a></li>
            <li><a href="../pages/cart.php">Cart</a></li>
            <li><a href="../pages/about.php">About Us</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="../pages/dashboard.php">Dashboard</a></li>
                <li><span>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span></li>
                <li><a href="../pages/logout.php">Logout</a></li>
                <?php if ($_SESSION['is_admin']): ?>
                    <li><a href="../admin/index.php">Admin Panel</a></li>
                <?php endif; ?>
            <?php else: ?>
                <li><a href="../pages/login.php">Login</a></li>
                <li><a href="../pages/register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div id="dark-mode-container">
        <button id="dark-mode-toggle" aria-label="Toggle dark mode" title="Toggle dark mode">
            <span id="moon-icon" class="icon">&#9790;</span>
            <span id="sun-icon" class="icon" style="display: none;">&#9728;</span>
        </button>
    </div>
</header>
<main>

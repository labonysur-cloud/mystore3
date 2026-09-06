<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Fetch summary data
// Total products
$stmt = $pdo->query("SELECT COUNT(*) FROM products");
$totalProducts = $stmt->fetchColumn();

// Total orders
$stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$totalOrders = $stmt->fetchColumn();

// Total users
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$totalUsers = $stmt->fetchColumn();

include '../includes/header.php';
?>

<style>
  .container {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background: #fffbea;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(255, 223, 0, 0.4);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  h1 {
    color: #b38f00;
    text-align: center;
    margin-bottom: 30px;
    text-shadow: 1px 1px 2px #f0e68c;
  }
  nav ul {
    display: flex;
    justify-content: center;
    gap: 30px;
    list-style: none;
    padding: 0;
    margin-bottom: 40px;
  }
  nav ul li a {
    text-decoration: none;
    color: #b38f00;
    font-weight: 600;
    padding: 8px 15px;
    border-radius: 20px;
    transition: background-color 0.3s ease, color 0.3s ease;
  }
  nav ul li a:hover {
    background-color: #b38f00;
    color: white;
  }
  .admin-summary {
    display: flex;
    justify-content: space-around;
    margin-bottom: 40px;
    font-size: 1.1rem;
    color: #6b5500;
  }
  .admin-summary div {
    background: #fff8b3;
    padding: 15px 25px;
    border-radius: 15px;
    box-shadow: 0 0 10px rgba(255, 223, 0, 0.3);
  }
  h2 {
    color: #b38f00;
    text-align: center;
    margin-bottom: 20px;
    text-shadow: 1px 1px 2px #f0e68c;
  }
  h3 {
    color: #a07a00;
    margin-bottom: 10px;
  }
  ul {
    list-style: disc inside;
    color: #5c4a00;
    margin-bottom: 30px;
  }
  ul li {
    padding: 5px 0;
  }
  p {
    text-align: center;
    color: #a07a00;
    font-style: italic;
  }
</style>

<div class="container">
  <h1>Admin Dashboard</h1>

  <nav>
      <ul>
          <li><a href="products.php">Manage Products</a></li>
          <li><a href="orders.php">Manage Orders</a></li>
          <li><a href="users.php">Manage Users</a></li>
      </ul>
  </nav>

  <div class="admin-summary">
      <div>Total Products: <?= $totalProducts ?></div>
      <div>Total Orders: <?= $totalOrders ?></div>
      <div>Total Users: <?= $totalUsers ?></div>
  </div>

  <h2>Reports</h2>

  <h3>Best Selling Products</h3>
  <?php if (empty($bestSelling)): ?>
      <p>No sales data available.</p>
  <?php else: ?>
      <ul>
          <?php foreach ($bestSelling as $product): ?>
              <li><?= htmlspecialchars($product['name']) ?> - Sold: <?= $product['total_sold'] ?></li>
          <?php endforeach; ?>
      </ul>
  <?php endif; ?>

  <h3>Out of Stock Products</h3>
  <?php if (empty($outOfStock)): ?>
      <p>All products are in stock.</p>
  <?php else: ?>
      <ul>
          <?php foreach ($outOfStock as $product): ?>
              <li><?= htmlspecialchars($product['name']) ?></li>
          <?php endforeach; ?>
      </ul>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

<?php
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: /mystore/pages/login.php');
    exit;
}

$userId = currentUserId();
$orders = getOrdersByUser($userId);

// Calculate summary data
$totalOrders = count($orders);
$pendingOrders = 0;
$totalSpent = 0.0;
foreach ($orders as $order) {
    if (strtolower($order['status']) === 'pending') {
        $pendingOrders++;
    }
    if (in_array(strtolower($order['status']), ['paid', 'shipped', 'delivered'])) {
        $totalSpent += $order['total'];
    }
}

// Get user info
global $pdo;
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

$userCartItems = getCartItems($userId);
$totalCartItems = 0;
$totalCartPrice = 0.0;
foreach ($userCartItems as $item) {
    $totalCartItems += $item['quantity'];
    $totalCartPrice += $item['price'] * $item['quantity'];
}

include '../includes/header.php';
?>

<style>
.dashboard-container {
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff8dc;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(183, 149, 11, 0.3);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: #b7950b;
    text-align: center;
}

.dashboard-nav {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-bottom: 30px;
}

.dashboard-nav a {
    text-decoration: none;
    color: #b7950b;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

.dashboard-nav a:hover,
.dashboard-nav a.active {
    background-color: #b7950b;
    color: #fff;
}

.dashboard-content {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    justify-content: center;
}

.profile-card {
    flex: 1 1 250px;
    background-color: #fff3b0;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(183, 149, 11, 0.3);
    text-align: center;
}

.profile-pic {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 15px;
    border: 3px solid #b7950b;
}

.profile-card h2 {
    font-size: 1.8rem;
    margin-bottom: 5px;
    color: #6e2c00;
}

.profile-card p {
    font-size: 1rem;
    color: #6e2c00;
    margin-bottom: 20px;
}

.profile-card table {
    width: 100%;
    border-collapse: collapse;
}

.profile-card th,
.profile-card td {
    padding: 10px;
    text-align: left;
}

.profile-card th {
    background-color: #f9e79f;
    color: #6e2c00;
}

.cart-summary {
    flex: 2 1 600px;
    background-color: #fff3b0;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(183, 149, 11, 0.3);
}

.cart-summary h3 {
    margin-bottom: 15px;
    color: #6e2c00;
}

.cart-summary p {
    font-size: 1rem;
    margin-bottom: 10px;
}

.cart-summary ul {
    list-style-type: disc;
    padding-left: 20px;
}

.order-history {
    flex: 1 1 100%;
    background-color: #fff3b0;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(183, 149, 11, 0.3);
    margin-top: 20px;
}

.order-history h3 {
    margin-bottom: 15px;
    color: #6e2c00;
}

.order-table {
    width: 100%;
    border-collapse: collapse;
}

.order-table th,
.order-table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.order-table th {
    background-color: #f9e79f;
    color: #6e2c00;
}

.order-table tr:hover {
    background-color: #f1e1a6;
}
</style>

<div class="dashboard-container">

    <h1 class="dashboard-title">My Store</h1>

    <nav class="dashboard-nav" role="navigation" aria-label="Dashboard Navigation">
        <a href="dashboard.php" class="active" aria-current="page">Dashboard</a>
        <a href="orders.php">Orders</a>
        <a href="account.php">Account</a>
        <a href="settings.php">Settings</a>
    </nav>

    <div class="dashboard-content">

        <aside class="profile-card" aria-label="User Profile">
            <?php
            $profileImages = [
                "https://i.pinimg.com/736x/f4/c7/1c/f4c71c4050c8b01d4ec39ab4185bd23a.jpg",
                "https://i.pinimg.com/1200x/3a/63/e6/3a63e6e6de9a3b18239fbccc6ecd684a.jpg",
                "https://i.pinimg.com/1200x/4f/44/b5/4f44b5604c68b01fe743ccda4c42d179.jpg"
            ];
            $randomImage = $profileImages[array_rand($profileImages)];
            ?>
            <img src="<?= $randomImage ?>" alt="Profile Picture" class="profile-pic" />
            <h2><?= htmlspecialchars($user['username']) ?></h2>
            <p><?= htmlspecialchars($user['email']) ?></p>

            <table>
                <tbody>
                    <tr>
                        <th>Total Orders</th>
                        <td><?= $totalOrders ?></td>
                    </tr>
                    <tr>
                        <th>Pending Orders</th>
                        <td><?= $pendingOrders ?></td>
                    </tr>
                    <tr>
                        <th>Total Spent</th>
                        <td>$<?= number_format($totalSpent, 2) ?></td>
                    </tr>
                    <tr>
                        <th>Cart Items</th>
                        <td><?= $totalCartItems ?></td>
                    </tr>
                    <tr>
                        <th>Cart Price</th>
                        <td>$<?= number_format($totalCartPrice, 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </aside>

        <section class="cart-summary" aria-label="Cart Summary">
            <h3>Cart Summary</h3>
            <?php if (empty($userCartItems)): ?>
                <p>Your cart is empty.</p>
            <?php else: ?>
                <p>Total Items: <?= $totalCartItems ?></p>
                <p>Total Price: $<?= number_format($totalCartPrice, 2) ?></p>
                <ul>
                    <?php foreach ($userCartItems as $item): ?>
                        <li><?= htmlspecialchars($item['name']) ?> - Quantity: <?= $item['quantity'] ?> - Price: $<?= number_format($item['price'], 2) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

        <section class="order-history" aria-label="Order History">
            <h3>Order History</h3>
            <?php if (empty($orders)): ?>
                <p>You have no orders yet.</p>
            <?php else: ?>
                <table class="order-table" role="table">
                    <thead>
                        <tr>
                            <th scope="col">Order ID</th>
                            <th scope="col">Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                    <tr tabindex="0">
                        <td>#<?= htmlspecialchars($order['id']) ?></td>
                        <td><?= date('F j, Y', strtotime($order['created_at'])) ?></td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                        <td>
                            $<?= number_format($order['total'], 2) ?><br />
                            <small>৳<?= number_format(convertUsdToBdt($order['total']), 2) ?></small>
                        </td>
                    </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

    </div>

</div>

<?php include '../includes/footer.php'; ?>

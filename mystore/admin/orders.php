<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Handle confirm order
if (isset($_GET['confirm_id'])) {
    $confirm_id = $_GET['confirm_id'];
    $stmt = $pdo->prepare("UPDATE orders SET status = 'Confirmed' WHERE id = ?");
    $stmt->execute([$confirm_id]);
    header('Location: orders.php');
    exit;
}

// Handle delete order
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$delete_id]);
    header('Location: orders.php');
    exit;
}

// Fetch orders with user info
$stmt = $pdo->query("SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
$orders = $stmt->fetchAll();

include '../includes/header.php';
?>

<h1>Manage Orders</h1>

<?php if (empty($orders)): ?>
    <p>No orders found.</p>
<?php else: ?>
    <?php
    $pendingCount = 0;
    foreach ($orders as $order) {
        if (strtolower($order['status']) === 'pending') {
            $pendingCount++;
        }
    }
    ?>
    <p><strong>Pending Orders: <?= $pendingCount ?></strong></p>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr style="<?= strtolower($order['status']) === 'pending' ? 'background-color: #fff3cd;' : '' ?>">
                    <td><?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['username']) ?></td>
                    <td><?= date('F j, Y', strtotime($order['created_at'])) ?></td>
                    <td><?= htmlspecialchars($order['status']) ?></td>
                    <td>$<?= number_format($order['total'], 2) ?></td>
                    <td>
                        <?php if (strtolower($order['status']) !== 'confirmed'): ?>
                            <a href="orders.php?confirm_id=<?= $order['id'] ?>" onclick="return confirm('Confirm this order?');">Confirm</a>
                        <?php endif; ?>
                        <a href="orders.php?delete_id=<?= $order['id'] ?>" onclick="return confirm('Delete this order?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

<?php
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = currentUserId();
$orders = getOrdersByUser($userId);

include '../includes/header.php';
?>

<h2>My Orders</h2>

<?php if (empty($orders)): ?>
    <p>You have no orders yet.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?= htmlspecialchars($order['id']) ?></td>
                <td><?= date('F j, Y', strtotime($order['created_at'])) ?></td>
                <td><?= htmlspecialchars($order['status']) ?></td>
                <td>$<?= number_format($order['total'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

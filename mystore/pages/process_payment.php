<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = currentUserId();
$orderId = $_GET['order_id'] ?? null;

if (!$orderId) {
    header('Location: products.php');
    exit;
}

// Fetch order details
global $pdo;
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$orderId, $userId]);
$order = $stmt->fetch();

if (!$order) {
    echo "Order not found or you do not have permission to view this order.";
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? 'Cash on Delivery';

    // For all payment methods, update order and set status to paid
    global $pdo;
    $stmt = $pdo->prepare("UPDATE orders SET payment_method = ?, status = 'paid' WHERE id = ?");
    $stmt->execute([$payment_method, $orderId]);

    // Show thank you message for non-Cash on Delivery payment methods
    if ($payment_method !== 'Cash on Delivery') {
        include '../includes/header.php';
        ?>
        <div style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); padding: 40px; border-radius: 15px; box-shadow: 0 8px 20px rgba(253, 160, 133, 0.5); margin: 30px auto; max-width: 600px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1.3rem; color: #4a2c00; text-align: center;">
            <h2 style="font-size: 2.5rem; margin-bottom: 25px; font-weight: 700;">Thank you for your order!</h2>
            <p>Your order ID is <strong><?= htmlspecialchars($orderId) ?></strong>.</p>
            <p>We appreciate your business and will process your order shortly.</p>
            <p><a href="products.php" style="color: #ffffff; text-decoration: underline; font-weight: 600;">Continue Shopping</a></p>
        </div>
        <?php
        include '../includes/footer.php';
        exit;
    } else {
        // For Cash on Delivery, redirect to checkout page with success message
        header('Location: checkout.php?order_id=' . $orderId);
        exit;
    }
}

include '../includes/header.php';
?>

<h2>Proceed to Payment</h2>

<?php if (!empty($errors)): ?>
    <div style="color: red; margin-bottom: 15px;">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post" action="process_payment.php?order_id=<?= htmlspecialchars($orderId) ?>">
    <label>
        <input type="radio" name="payment_method" value="bKash" required>
        bKash
    </label><br>
    <label>
        <input type="radio" name="payment_method" value="Nagad" required>
        Nagad
    </label><br>
    <label>
        <input type="radio" name="payment_method" value="Rocket" required>
        Rocket
    </label><br>
    <label>
        <input type="radio" name="payment_method" value="Cash on Delivery" required>
        Cash on Delivery
    </label><br><br>
    <button type="submit" style="width: 100%; padding: 15px; font-size: 1.2rem; background-color: #d4af37; color: white; border: none; border-radius: 8px; cursor: pointer;">
        Proceed to Payment
    </button>
</form>

<?php include '../includes/footer.php'; ?>

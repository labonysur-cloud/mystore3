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

// Handle add, update, remove actions
$action = $_GET['action'] ?? '';
$productId = $_GET['id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if ($action === 'add' && $productId) {
    error_log("Adding to cart: userId=$userId, productId=$productId, quantity=$quantity");
    addToCart($userId, $productId, (int)$quantity);
    header('Location: cart.php');
    exit;
}
if ($action === 'update' && isset($_POST['cart_item_id']) && isset($_POST['quantity'])) {
    $cartItemIds = $_POST['cart_item_id'];
    $quantities = $_POST['quantity'];
    foreach ($cartItemIds as $index => $cartItemId) {
        $qty = (int)$quantities[$index];
        updateCartItem($cartItemId, $qty);
    }
    header('Location: cart.php');
    exit;
}
if ($action === 'remove' && isset($_GET['cart_item_id'])) {
    $cartItemId = $_GET['cart_item_id'];
    removeCartItem($cartItemId);
    header('Location: cart.php');
    exit;
}

$cartItems = getCartItems($userId);
error_log("Cart items count for user $userId: " . count($cartItems));

include '../includes/header.php';
?>

<h2>Your Shopping Cart</h2>

<?php if (empty($cartItems)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <form method="post" action="cart.php?action=update">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($cartItems as $item):
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                    <td>
                    <input type="number" name="quantity[]" value="<?= $item['quantity'] ?>" min="1" required>
                    <input type="hidden" name="cart_item_id[]" value="<?= $item['id'] ?>">
                    </td>
                    <td>$<?= number_format($subtotal, 2) ?></td>
                    <td><a href="cart.php?action=remove&cart_item_id=<?= $item['id'] ?>" onclick="return confirm('Remove this item?');">Remove</a></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" style="text-align:right;"><strong>Total:</strong></td>
                    <td colspan="2"><strong>$<?= number_format($total, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>
        <button type="submit">Update Cart</button>
    </form>
    <br>
    <a href="checkout.php" class="order-link">Proceed to Checkout</a>
<?php endif; ?>

<style>
.order-link {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 24px;
    background-color: #d4af37;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}
.order-link:hover {
    background-color: #b38b2a;
}
</style>

<?php include '../includes/footer.php'; ?>

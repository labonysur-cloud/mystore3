<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = currentUserId();
error_log("Checkout page userId: $userId");
$errors = [];
$success = false;
$orderId = null;

// Check for success message in session
if (isset($_SESSION['order_success']) && $_SESSION['order_success'] === true) {
    $success = true;
    $orderId = $_SESSION['order_id'] ?? null;
    unset($_SESSION['order_success'], $_SESSION['order_id']);
    // Also set a flag to show thank you message on this page
    $showThankYou = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? 'Cash on Delivery'; // Default to COD

    // Debug logging
    error_log("Checkout POST request by userId: $userId");

    // Calculate total from cart items
    $cartItems = getCartItems($userId);
    error_log("Cart items count: " . count($cartItems));
    error_log("Cart items detail: " . print_r($cartItems, true));

    if ($cartItems === false || empty($cartItems)) {
        $errors[] = "Failed to place order. Your cart may be empty.";
        error_log("Order placement failed: Cart is empty or error retrieving cart for userId $userId");
    } else {
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        error_log("Order total calculated: $total");

        $orderId = createOrder($userId, $total);
        if ($orderId) {
            error_log("Order created with ID: $orderId");
            // Add order items
            foreach ($cartItems as $item) {
                addOrderItem($orderId, $item['product_id'], $item['quantity'], $item['price']);
            }

            // Save payment method in orders table or as order meta (assuming orders table has payment_method column)
            global $pdo;
            $stmt = $pdo->prepare("UPDATE orders SET payment_method = ? WHERE id = ?");
            $stmt->execute([$payment_method, $orderId]);

            // Clear cart
            clearCart($userId);

            if ($payment_method === 'Cash on Delivery') {
                // Set session success and redirect to avoid form resubmission
                $_SESSION['order_success'] = true;
                $_SESSION['order_id'] = $orderId;
                header('Location: checkout.php');
                exit;
            } else {
                // For AJAX request, respond with JSON containing redirect URL
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['redirect' => 'process_payment.php?order_id=' . $orderId]);
                    exit;
                } else {
                    // Redirect to process_payment.php for other payment methods
                    ob_end_clean();
                    header('Location: process_payment.php?order_id=' . $orderId);
                    exit;
                }
            }
        } else {
            $errors[] = "Failed to place order. Please try again.";
            error_log("Order creation failed for userId $userId");
        }
    }
}

include '../includes/header.php';
?>

<h2>Checkout</h2>

<?php
if ((isset($_GET['order_id'])) || !empty($showThankYou)):
    if (!empty($showThankYou)) {
        // Redirect to unify display logic with query parameters
        header('Location: checkout.php?order_id=' . $orderId);
        exit;
    } else {
        $orderId = (int)$_GET['order_id'];
    }
?>
    <div class="thank-you-message" style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); padding: 40px; border-radius: 15px; box-shadow: 0 8px 20px rgba(253, 160, 133, 0.5); margin-bottom: 30px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1.3rem; color: #4a2c00; text-align: center;">
        <h2 style="font-size: 2.5rem; margin-bottom: 25px; font-weight: 700;">Thank you for your order!</h2>
        <p>Your order ID is <strong><?= $orderId ?></strong>.</p>
        <p>We appreciate your business and will process your order shortly.</p>
        <p><a href="products.php" style="color: #ffffff; text-decoration: underline; font-weight: 600;">Continue Shopping</a></p>
    </div>
<?php else: ?>
    <?php if ($errors): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <p>Please confirm your order by clicking the button below.</p>

    <form id="checkout-form" method="post" action="checkout.php">
        <label>
            <input type="radio" name="payment_method" value="Cash on Delivery" checked>
            <img src="https://png.pngtree.com/png-clipart/20230405/original/pngtree-cash-on-delivery-red-vector-logo-with-hand-giving-money-png-image_9029148.png" alt="Cash on Delivery" style="height: 40px; vertical-align: middle;">
            Cash on Delivery
        </label>
        <br>
        <label>
            <input type="radio" name="payment_method" value="bKash">
            <img src="https://www.logoshape.com/wp-content/uploads/2025/02/Bkash_vector_logo.png" alt="bKash" style="height: 40px; vertical-align: middle;">
            bKash
        </label>
        <br>
        <label>
            <input type="radio" name="payment_method" value="Nagad">
            <img src="https://vectorseek.com/wp-content/uploads/2022/02/vectorseek.com-Nagad-Logo-Vector.png" alt="Nagad" style="height: 40px; vertical-align: middle;">
            Nagad
        </label>
        <br>
        <label>
            <input type="radio" name="payment_method" value="Rocket">
            <img src="https://upload.wikimedia.org/wikipedia/commons/e/e9/Rocket_ddbl.png" alt="Rocket" style="height: 40px; vertical-align: middle;">
            Rocket
        </label>
        <br>
        <label>
            <input type="radio" name="payment_method" value="Visa Card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa Card" style="height: 40px; vertical-align: middle;">
            Visa Card
        </label>
        <br>
        <label>
            <input type="radio" name="payment_method" value="Dutch Bangla Card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/16/Dutch-bangla-bank-ltd.svg/2048px-Dutch-bangla-bank-ltd.svg.png" alt="Dutch Bangla Card" style="height: 40px; vertical-align: middle;">
            Dutch Bangla Card
        </label>
        <br><br>
        <button type="submit">Place Order</button>
    </form>

    <script>
        document.getElementById('checkout-form').addEventListener('submit', function(event) {
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;
            if (selectedPayment !== 'Cash on Delivery') {
                event.preventDefault();
                // Submit form via fetch to get order ID and redirect
                const formData = new FormData(this);
                fetch('checkout.php', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.redirect) {
                        // Redirect to process_payment.php but show payment options in same style as checkout page
                        window.location.href = data.redirect + '&show_payment_options=1';
                    } else {
                        alert('Order placed but unable to redirect to payment options.');
                    }
                })
                .catch(() => {
                    alert('Error placing order. Please try again.');
                });
            }
        });
    </script>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

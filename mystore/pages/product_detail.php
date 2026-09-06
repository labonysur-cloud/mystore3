<?php
require_once '../includes/functions.php';

$productId = $_GET['id'] ?? null;
if (!$productId) {
    header('Location: products.php');
    exit;
}

$product = getProductById($productId);
if (!$product) {
    header('Location: products.php');
    exit;
}

include '../includes/header.php';
?>

<h2><?= htmlspecialchars($product['name']) ?></h2>

<div class="product-detail">
    <!-- Image path: <?php echo htmlspecialchars($product['image'] ?? 'assets/images/default.png'); ?> -->
    <?php
        $imageSrc = $product['image'] ?? 'assets/images/default.png';
        if (preg_match('/^https?:\/\//', $imageSrc)) {
            $imgUrl = $imageSrc;
        } else {
            $imgUrl = '../' . $imageSrc;
        }
    ?>
    <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="300" height="300">
    <p>Category: <?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></p>
    <p>Price: $<?= number_format($product['price'], 2) ?></p>
    <p>Description: <?= nl2br(htmlspecialchars($product['description'])) ?></p>

    <form method="post" action="cart.php?action=add&id=<?= $product['id'] ?>">
        <label>Quantity:
            <input type="number" name="quantity" value="1" min="1" required>
        </label>
        <button type="submit">Add to Cart</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

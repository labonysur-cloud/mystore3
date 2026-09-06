<?php
require_once '../includes/functions.php';

$main_category_id = $_GET['main_category_id'] ?? null;
$subcategory_id = $_GET['subcategory_id'] ?? null;

if ($subcategory_id) {
    $products = getProductsByCategory($subcategory_id);
} elseif ($main_category_id) {
    // Fetch products for all subcategories under main category
    $subcategories = getSubcategories($main_category_id);
    $subcat_ids = array_column($subcategories, 'id');
    if (!empty($subcat_ids)) {
        $products = getProductsByCategories($subcat_ids);
    } else {
        $products = [];
    }
} else {
    $products = getAllProducts();
}

$main_categories = getMainCategories();
$subcategories = $main_category_id ? getSubcategories($main_category_id) : [];

include '../includes/header.php';
?>

<h2>Products</h2>

<form method="get" action="products.php" class="category-filter-form">
    <label for="main_category">Main Category:</label>
    <select name="main_category_id" id="main_category" onchange="this.form.submit()">
        <option value="">Select Main Category</option>
        <?php foreach ($main_categories as $main_cat): ?>
            <option value="<?= $main_cat['id'] ?>" <?= ($main_category_id == $main_cat['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($main_cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <?php if (!empty($subcategories)): ?>
        <label for="subcategory">Subcategory:</label>
        <select name="subcategory_id" id="subcategory" onchange="this.form.submit()">
            <option value="">Select Subcategory</option>
            <?php foreach ($subcategories as $subcat): ?>
                <option value="<?= $subcat['id'] ?>" <?= ($subcategory_id == $subcat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($subcat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
</form>

<div class="product-list">
    <?php if (empty($products)): ?>
        <p>No products found.</p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <a href="product_detail.php?id=<?= $product['id'] ?>">
                    <!-- Image path: <?php echo htmlspecialchars($product['image'] ?? 'assets/images/default.png'); ?> -->
                    <?php
                        $imageSrc = $product['image'] ?? 'assets/images/default.png';

                        // Check if imageSrc is a full URL
                        if (preg_match('/^https?:\/\//', $imageSrc)) {
                            $imgUrl = $imageSrc;
                        } else {
                            // For relative paths, prepend base path and check if file exists
                            $relativePath = ltrim($imageSrc, '/');
                            $basePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $relativePath;
                            if (file_exists($basePath) && !is_dir($basePath)) {
                                $imgUrl = '/' . $relativePath;
                            } else {
                                // Fallback to default image if file missing
                                $imgUrl = '/assets/images/default.png';
                            }
                        }
                    ?>
                    <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy" decoding="async" width="280" height="280" style="object-fit: cover; border-radius: 12px;">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                </a>
                <p>Category: <?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></p>
                <p class="price">$<?= number_format($product['price'], 2) ?></p>
                <form method="post" action="cart.php?action=add&id=<?= $product['id'] ?>" class="add-to-cart-form" style="display:flex; justify-content:center; align-items:center; gap:10px;">
                    <input type="number" name="quantity" value="1" min="1" required style="width:60px; padding:8px; border-radius:6px; border:1px solid #f2c2d9; text-align:center;">
                    <button type="submit" style="background-color:#f2c94c; color:#fff; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; font-weight:700; font-size:1rem; transition:background-color 0.3s ease; box-shadow:none; max-width:150px;">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Handle add product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? null;
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;

    $imagePath = '';
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/images/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileTmpPath = $_FILES['image_file']['tmp_name'];
        $fileName = basename($_FILES['image_file']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExt, $allowedExts)) {
            $newFileName = uniqid('prod_', true) . '.' . $fileExt;
            $destPath = $uploadDir . $newFileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $imagePath = 'assets/images/products/' . $newFileName;
            } else {
                error_log('Failed to move uploaded file to ' . $destPath);
                echo "<p style='color:red;'>Error: Failed to move uploaded file.</p>";
            }
        } else {
            error_log('Invalid file extension: ' . $fileExt);
            echo "<p style='color:red;'>Error: Invalid file extension. Allowed: jpg, jpeg, png, gif.</p>";
        }
    } else {
        if (isset($_FILES['image_file'])) {
            error_log('File upload error code: ' . $_FILES['image_file']['error']);
            echo "<p style='color:red;'>Error: File upload error code " . $_FILES['image_file']['error'] . ".</p>";
        }
    }

    if ($name && $category_id && $price >= 0 && $stock >= 0) {
        $stmt = $pdo->prepare("INSERT INTO products (name, category_id, price, stock, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $category_id, $price, $stock, $imagePath]);
        header('Location: products.php');
        exit;
    }
}

// Handle delete product
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$delete_id]);
    header('Location: products.php');
    exit;
}

// Fetch products and categories
$stmt = $pdo->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
$products = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll();

include '../includes/header.php';
?>

<h1>Manage Products</h1>

<h2>Add New Product</h2>
<form method="post" action="products.php" enctype="multipart/form-data">
    <input type="hidden" name="add_product" value="1" />
    <label>Name:</label>
    <input type="text" name="name" required />
    <label>Category:</label>
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <label>Price:</label>
    <input type="number" step="0.01" name="price" min="0" required />
    <label>Stock:</label>
    <input type="number" name="stock" min="0" required />
    <label>Image File:</label>
    <input type="file" name="image_file" accept="image/*" />
    <button type="submit">Add Product</button>
</form>

<h2>Existing Products</h2>
<?php if (empty($products)): ?>
    <p>No products found.</p>
<?php else: ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></td>
                    <td>$<?= number_format($product['price'], 2) ?></td>
                    <td><?= $product['stock'] ?></td>
                    <td>
                        <?php if ($product['image']): ?>
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width:50px; height:50px; object-fit:cover;" />
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="products.php?delete_id=<?= $product['id'] ?>" onclick="return confirm('Delete this product?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

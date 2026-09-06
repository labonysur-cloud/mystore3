<?php
require_once 'db.php';

// Get all categories
function getCategories() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    return $stmt->fetchAll();
}

// Get main categories (parent_id IS NULL)
function getMainCategories() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name");
    return $stmt->fetchAll();
}

// Get subcategories by parent category id
function getSubcategories($parent_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE parent_id = ? ORDER BY name");
    $stmt->execute([$parent_id]);
    return $stmt->fetchAll();
}

// Get products by multiple category ids
function getProductsByCategories(array $category_ids) {
    global $pdo;
    if (empty($category_ids)) {
        return [];
    }
    $placeholders = implode(',', array_fill(0, count($category_ids), '?'));
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.category_id IN ($placeholders) ORDER BY p.created_at DESC");
    $stmt->execute($category_ids);
    return $stmt->fetchAll();
}

// Get all products with category name
function getAllProducts() {
    global $pdo;
    $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC");
    return $stmt->fetchAll();
}

// Get products by category id
function getProductsByCategory($category_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? ORDER BY p.created_at DESC");
    $stmt->execute([$category_id]);
    return $stmt->fetchAll();
}

// Get product by id
function getProductById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Add product to cart
function addToCart($user_id, $product_id, $quantity) {
    global $pdo;
    // Check if product already in cart
    $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $item = $stmt->fetch();
    if ($item) {
        // Update quantity
        $new_quantity = $item['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        return $stmt->execute([$new_quantity, $item['id']]);
    } else {
        // Insert new
        $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $product_id, $quantity]);
    }
}

// Get cart items for user
function getCartItems($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT ci.*, p.name, p.price, p.image FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// Update cart item quantity
function updateCartItem($cart_item_id, $quantity) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
    return $stmt->execute([$quantity, $cart_item_id]);
}

// Remove cart item
function removeCartItem($cart_item_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = ?");
    return $stmt->execute([$cart_item_id]);
}

// Clear cart for user
function clearCart($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
    return $stmt->execute([$user_id]);
}

// Create order from cart
function createOrder($user_id, $total) {
    global $pdo;
    try {
        if (empty($user_id) || $total <= 0) {
            error_log("createOrder: Invalid user_id or total. user_id=$user_id, total=$total");
            return false;
        }
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
        if ($stmt->execute([$user_id, $total])) {
            return $pdo->lastInsertId();
        }
    } catch (PDOException $e) {
        error_log("createOrder PDOException: " . $e->getMessage());
    }
    return false;
}

// Add order items
function addOrderItem($order_id, $product_id, $quantity, $price) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$order_id, $product_id, $quantity, $price]);
}

// Get orders for user
function getOrdersByUser($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// Get order items by order id
function getOrderItems($order_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
    $stmt->execute([$order_id]);
    return $stmt->fetchAll();
}

// User registration
function registerUser($username, $email, $password) {
    global $pdo;
    // Auto-generate username from email if not provided
    if (empty($username)) {
        $username = strstr($email, '@', true);
    }
    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetchColumn() > 0) {
        // Username or email already exists
        return false;
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, false)");
    return $stmt->execute([$username, $email, $hashed_password]);
}

// User login by username or email
function loginUser($usernameOrEmail, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

// Get user by id
function getUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Check if user is logged in
function isLoggedIn() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user_id']);
}

// Get current logged in user id
function currentUserId() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    return $_SESSION['user_id'] ?? null;
}

// Logout user
function logoutUser() {
    session_start();
    session_unset();
    session_destroy();
}

// Convert USD to BDT (Taka) with fixed rate
function convertUsdToBdt($usdAmount) {
    $conversionRate = 108.0; // Example fixed rate: 1 USD = 108 BDT
    return $usdAmount * $conversionRate;
}

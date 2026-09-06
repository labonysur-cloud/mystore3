<?php
require_once '../includes/functions.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = $_POST['usernameOrEmail'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = loginUser($usernameOrEmail, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];
        if ($user['is_admin']) {
            header('Location: ../admin/index.php');
        } else {
            header('Location: ../pages/dashboard.php');
        }
        exit();
    } else {
        $error = 'Invalid username/email or password.';
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Login</h2>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" action="login.php">
    <label for="usernameOrEmail">Username or Email:</label>
    <input type="text" name="usernameOrEmail" id="usernameOrEmail" required>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>

    <button type="submit">Login</button>
</form>

<?php include '../includes/footer.php'; ?>

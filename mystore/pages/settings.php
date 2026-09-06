<?php
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = currentUserId();
$errors = [];
$success = false;

// Fetch current user info
global $pdo;
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email)) {
        $errors[] = "Username and email cannot be empty.";
    } else {
        // Check if username or email already exists for other users
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$username, $email, $userId]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Username or email already taken by another user.";
        } else {
            // Update username and email
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $updated = $stmt->execute([$username, $email, $userId]);

            // Update password if provided
            if ($updated && !empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $updated = $stmt->execute([$hashed_password, $userId]);
            }

            if ($updated) {
                $success = true;
                // Refresh user info
                $stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $user = $stmt->fetch();
            } else {
                $errors[] = "Failed to update user information.";
            }
        }
    }
}

include '../includes/header.php';
?>

<h2>User Settings</h2>

<?php if ($success): ?>
    <p style="color: green;">Settings updated successfully.</p>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="errors" style="color: red;">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="settings.php">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <label for="password">New Password (leave blank to keep current):</label>
    <input type="password" id="password" name="password">

    <button type="submit">Update Settings</button>
</form>

<?php include '../includes/footer.php'; ?>

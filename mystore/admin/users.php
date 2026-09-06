<?php
require_once '../includes/functions.php';

if (!isLoggedIn() || !$_SESSION['is_admin']) {
    header('Location: ../pages/login.php');
    exit;
}

global $pdo;

$errors = [];
$success = false;

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    if ($deleteId != $_SESSION['user_id']) { // Prevent deleting self
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$deleteId]);
        $success = "User deleted successfully.";
    } else {
        $errors[] = "You cannot delete your own account.";
    }
}

// Fetch all users
$stmt = $pdo->query("SELECT id, username, email, is_admin, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

include '../includes/header.php';
?>

<h2>Manage Users</h2>

<?php if ($errors): ?>
    <div class="errors">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Admin</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['is_admin'] ? 'Yes' : 'No' ?></td>
                <td><?= htmlspecialchars($user['created_at']) ?></td>
                <td>
                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <a href="users.php?delete_id=<?= $user['id'] ?>" onclick="return confirm('Delete this user?');">Delete</a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>

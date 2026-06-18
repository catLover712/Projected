<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: /Projected/views/login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

global $pdo;
$userModel = new UserModel($pdo);
$user = $userModel->getUserById($_SESSION['user_id']);

if (!$user) {
    session_destroy();
    header("Location: /Projected/views/login.php");
    exit();
}

$errors = [
    'missing_username' => 'Username cannot be empty.',
    'username_taken'   => 'That username is already taken.',
    'password_mismatch'=> 'Passwords do not match.',
    'server'           => 'Something went wrong. Please try again.',
];
$successes = [
    'updated' => 'Profile updated successfully!',
];
$errorMsg   = isset($_GET['error'])   ? ($errors[$_GET['error']]     ?? 'An error occurred.') : null;
$successMsg = isset($_GET['success']) ? ($successes[$_GET['success']] ?? null) : null;

require __DIR__ . '/../includes/header.php';
?>

<div class="full-container">

    <a href="/Projected/index.php" class="back-button">⮜</a>

    <div class="project-container" style="flex-direction: column; align-items: center; margin-bottom: 50px;">

        <h2 style="margin-bottom: 20px; color: #124212;">Edit Profile</h2>

        <form method="post" action="../controllers/UserController.php" style="width: 100%; max-width: 400px;">

            <input type="hidden" name="action" value="update_profile">

            <div id="form-group">

                <div style="margin-bottom: 15px;">
                    <label for="username" style="color: #124212; font-weight: bold;">Username *</label><br />
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" maxlength="15" required style="width: 100%; padding: 10px; border-radius: 3px; border: 1px solid #ccc;" />
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="color: #124212; font-weight: bold;">Email</label><br />
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled style="width: 100%; padding: 10px; border-radius: 3px; border: 1px solid #ccc; background-color: #e9ecef;" />
                    <small style="color: #6c757d;">Email cannot be changed.</small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="password" style="color: #124212; font-weight: bold;">New Password (optional)</label><br />
                    <input type="password" id="password" name="password" placeholder="Leave empty to keep current" style="width: 100%; padding: 10px; border-radius: 3px; border: 1px solid #ccc;" />
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="confirm_password" style="color: #124212; font-weight: bold;">Confirm New Password</label><br />
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" style="width: 100%; padding: 10px; border-radius: 3px; border: 1px solid #ccc;" />
                </div>

            </div>

            <div class="project-actions" style="margin-bottom: 20px;">
                <button type="submit" class="btn" style="width: 100%; background-color: #F4ECDC;">Save Profile</button>
            </div>

        </form>

        <hr style="width: 100%; max-width: 400px; border: 0; border-top: 1px solid #ccc; margin: 20px 0;" />

        <div style="width: 100%; max-width: 400px;">
            <button onclick="openDeleteModal(event)" class="btn" style="width: 100%; background-color: #d9534f; color: white;">Delete Profile</button>
        </div>

    </div>

</div>

<!-- Profile Delete Confirmation Modal -->
<div id="delete-modal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
    <div class="modal-content" style="background: #DDCBB6; padding: 30px; border-radius: 8px; width: 400px; max-width: 90%; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.3); border: 2px solid #124212; transform: scale(0.9); transition: transform 0.3s ease;">
        <h3 style="color: #124212; margin-bottom: 15px; font-size: 1.5rem; font-family: 'Bitcount', sans-serif;">Delete Profile</h3>
        <p style="color: #124212; margin-bottom: 25px; font-size: 1.1rem;">Are you sure? This will permanently delete your account and all your projects.</p>
        <div style="display: flex; gap: 15px; justify-content: center;">
            <a href="../controllers/UserController.php?delete_profile=1" class="btn" style="background: #d9534f; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px; font-family: 'Bitcount', sans-serif; display: inline-flex; align-items: center; justify-content: center;">
                Yes, Delete
            </a>
            <button onclick="closeDeleteModal()" class="btn" style="background: #7b815a; color: white; padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; font-family: 'Bitcount', sans-serif;">
                Cancel
            </button>
        </div>
    </div>
</div>

<?php if ($errorMsg || $successMsg): ?>
<div id="toast" class="toast <?= $errorMsg ? 'toast-error' : 'toast-success' ?>">
    <span class="toast-icon"><?= $errorMsg ? '✕' : '✓' ?></span>
    <span class="toast-msg"><?= htmlspecialchars($errorMsg ?? $successMsg) ?></span>
    <button class="toast-close" onclick="dismissToast()">×</button>
</div>
<?php endif; ?>


<script>
(function() {
    const toast = document.getElementById('toast');
    if (toast) {
        requestAnimationFrame(() => {
            requestAnimationFrame(() => toast.classList.add('show'));
        });
        const timer = setTimeout(dismissToast, 5000);
        function dismissToast() {
            clearTimeout(timer);
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }
        window.dismissToast = dismissToast;
    }
})();

function openDeleteModal(event) {
    event.preventDefault();
    const modal = document.getElementById('delete-modal');
    modal.style.display = 'flex';
    setTimeout(() => modal.querySelector('.modal-content').style.transform = 'scale(1)', 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('delete-modal');
    modal.querySelector('.modal-content').style.transform = 'scale(0.9)';
    setTimeout(() => modal.style.display = 'none', 150);
}
</script>

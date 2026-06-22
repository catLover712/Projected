<?php
$errors = [
    'missing'       => 'Please fill in all fields.',
    'invalid_email' => 'Please enter a valid email address.',
    'not_found'     => 'No account found with that email address.',
    'wrong_password' => 'Incorrect password. Please try again.',
];
$successes = [
    'registered' => 'Account created! You can now log in.',
];
$errorMsg   = isset($_GET['error'])   ? ($errors[$_GET['error']]   ?? 'An error occurred.') : null;
$successMsg = isset($_GET['success']) ? ($successes[$_GET['success']] ?? null) : null;

require __DIR__ . '/../includes/header.php';
?>

<div class="full-container">

    <div class="project-container" style="flex-direction: column; align-items: center; margin-bottom: 50px;">

        <h2 style="margin-bottom: 20px;">Login</h2>

        <form method="post" action="../controllers/UserController.php" style="width: 100%; max-width: 400px;">

            <div id="form-group">

                <div style="margin-bottom: 15px;">
                    <label for="email">Email *</label><br />
                    <input type="email" id="email" name="email" required style="width: 100%; padding: 10px;" />
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="password">Password *</label><br />
                    <input type="password" id="password" name="password" required style="width: 100%; padding: 10px;" />
                </div>

            </div>

            <div class="project-actions">
                <button type="submit" class="btn">Login</button>
            </div>

        </form>

        <p style="margin-top: 15px;">
            No account yet?
            <a href="register.php">Register</a>
        </p>

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
    if (!toast) return;
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
})();
</script>
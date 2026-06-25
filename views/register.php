<?php
$errors = [
    'missing'           => 'Please fill in all fields.',
    'invalid_username'  => 'Username must be between 3 and 15 characters.',
    'invalid_email'     => 'Please enter a valid email address (max 50 characters).',
    'weak_password'     => 'Password must be at least 6 characters long.',
    'password_mismatch' => 'Passwords do not match.',
    'already_exists'    => 'That username or email is already taken.',
    'server'            => 'Something went wrong. Please try again.',
];

$errorMsg = isset($_GET['error'])
    ? ($errors[$_GET['error']] ?? 'An error occurred.')
    : null;

require __DIR__ . '/../includes/header.php';
?>

<div class="full-container">

    <div class="project-container"
         style="flex-direction: column; align-items: center; margin-bottom: 50px;">

        <h2 style="margin-bottom: 20px;">Register</h2>

        <form method="post"
              action="../controllers/UserController.php"
              style="width: 100%; max-width: 400px;">

            <div id="form-group">

                <!-- USERNAME -->
                <div style="margin-bottom: 15px;">
                    <label for="username">Username *</label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        minlength="3"
                        maxlength="15"
                        autocomplete="username"
                        class="form-input"
                        style="width: 100%; padding: 10px;"
                    >
                </div>

                <!-- EMAIL -->
                <div style="margin-bottom: 15px;">
                    <label for="email">Email *</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        maxlength="50"
                        autocomplete="email"
                        class="form-input"
                        style="width: 100%; padding: 10px;"
                    >
                </div>

                <!-- PASSWORD -->
                <div style="margin-bottom: 15px;">
                    <label for="password">Password *</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        minlength="6"
                        autocomplete="new-password"
                        class="form-input"
                        style="width: 100%; padding: 10px;"
                    >
                </div>

                <!-- CONFIRM PASSWORD -->
                <div style="margin-bottom: 15px;">
                    <label for="confirm_password">Confirm Password *</label>

                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        required
                        minlength="6"
                        autocomplete="new-password"
                        class="form-input"
                        style="width: 100%; padding: 10px;"
                    >
                </div>

            </div>

            <div class="project-actions">
                <button type="submit" class="btn">Submit</button>
            </div>

        </form>

        <p style="margin-top: 15px;">
            Already have an account?
            <a href="login.php">Login</a>
        </p>

    </div>

</div>

<?php if ($errorMsg): ?>
    <div id="toast" class="toast toast-error">
        <span class="toast-icon">✕</span>
        <span class="toast-msg">
            <?= htmlspecialchars($errorMsg) ?>
        </span>
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
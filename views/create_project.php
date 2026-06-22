<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: /Projected/views/login.php");
    exit();
}

$errors = [
    'missing_title'       => 'Title is required.',
    'invalid_title'       => 'Title must be between 3 and 45 characters.',
    'description_too_long'=> 'Description cannot exceed 300 characters.',
    'file_too_large'      => 'File size cannot exceed 2 MB.',
    'invalid_file_type'   => 'Only JPG, PNG, and GIF images are allowed.',
    'server'              => 'Something went wrong. Please try again.',
];

$errorMsg = isset($_GET['error'])
    ? ($errors[$_GET['error']] ?? 'An error occurred.')
    : null;

require 'includes/header.php';
?>

<div class="full-container">

    <a href="index.php" class="back-button">⮜</a>

    <div class="project-container" style="margin-bottom: 50px;">

        <div class="project-image" style="width: 500px; height: 318px; overflow: hidden; border-radius: 3px; flex-shrink: 0;">
            <img
                id="image-preview"
                src="assets/images/missing_image.png"
                alt="Project Picture Preview"
                style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: block;"
            >
        </div>

        <div class="project-info">

            <h2 style="margin-bottom: 20px; color: #124212;">Create New Project</h2>

            <form method="POST" enctype="multipart/form-data" class="edit-form">

                <div style="margin-bottom: 15px;">
                    <label for="title" style="display: block; margin-bottom: 5px; color: #124212; font-weight: bold; font-size: 1.1rem;">
                        Title *
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        required
                        minlength="3"
                        maxlength="45"
                        placeholder="Enter project title (min 3, max 45 chars)"
                        class="edit-input"
                    >
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="description" style="display: block; margin-bottom: 5px; color: #124212; font-weight: bold; font-size: 1.1rem;">
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        maxlength="300"
                        placeholder="Describe your project..."
                        class="edit-textarea"
                        style="resize: none;"
                    ></textarea>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="picture" style="display: block; margin-bottom: 5px; color: #124212; font-weight: bold; font-size: 1.1rem;">
                        Project Picture
                    </label>
                    <input
                        type="file"
                        id="picture"
                        name="picture"
                        accept="image/*"
                        class="edit-input"
                        style="background: white; border: 1px solid #ccc; font-family: inherit;"
                        onchange="previewImage(event)"
                    >
                </div>

                <button type="submit" class="action-btn save-btn">
                    Create Project
                </button>

            </form>

        </div>

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
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('image-preview');
        output.src = reader.result;
    };
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}

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

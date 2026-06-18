<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: /Projected/views/login.php");
    exit();
}

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
                        maxlength="45"
                        placeholder="Enter project title (max 45 chars)"
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
</script>

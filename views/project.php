<?php
/** @var array|null $project */

if (!isset($project)) {
    header("Location: /Projected/index.php");
    exit();
}

$errors = [
    'invalid_title'       => 'Title must be between 3 and 45 characters.',
    'description_too_long'=> 'Description cannot exceed 300 characters.',
    'file_too_large'      => 'File size cannot exceed 2 MB.',
    'invalid_file_type'   => 'Only JPG, PNG, and GIF images are allowed.',
];

$errorMsg = isset($_GET['error'])
    ? ($errors[$_GET['error']] ?? 'An error occurred.')
    : null;

require 'includes/header.php';
?>

<?php
$image = 'assets/images/missing_image.png';
if (!empty($project['picture'])) {
    if (is_string($project['picture']) && file_exists($project['picture'])) {
        $image = $project['picture'];
    } else {
        $image = 'data:image/jpeg;base64,' . base64_encode($project['picture']);
    }
}

$editMode = isset($_GET['edit']) && $_GET['edit'] == 1;


?>

<?php if ($project): ?>

    <div class="full-container">

        <a href="index.php" class="back-button">⮜</a>

        <div class="project-container" style="margin-bottom: 50px;">

            <div class="project-image" style="width: 500px; height: 318px; overflow: hidden; border-radius: 3px; flex-shrink: 0;">
                <img
                    id="image-preview"
                    src="<?= $image ?>"
                    alt="<?= htmlspecialchars($project['title']) ?>"
                    style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: block;"
                >
            </div>

            <div class="project-info">

                <div class="project-actions">

                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $project['fk_user_id']): ?>
                        <?php if (!$editMode): ?>
                            <a class="action-btn edit"
                            href="index.php?page=project&id=<?= $project['id'] ?>&edit=1">
                                Edit
                            </a>
                        <?php else: ?>
                            <a class="action-btn discard"
                            href="index.php?page=project&id=<?= $project['id'] ?>">
                                Discard
                            </a>
                        <?php endif; ?>

                        <a class="action-btn delete"
                        href="#"
                        onclick="openDeleteModal(event)">
                            Delete
                        </a>
                    <?php endif; ?>

                </div>

                <?php if ($editMode): ?>

                    <form method="POST" enctype="multipart/form-data" class="edit-form">

                        <div style="margin-bottom: 10px;">
                            <label for="title" style="display: block; margin-bottom: 5px; color: black; font-weight: bold;">Title *</label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="<?= htmlspecialchars($project['title']) ?>"
                                required
                                minlength="3"
                                maxlength="45"
                                class="edit-input"
                            >
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label for="description" style="display: block; margin-bottom: 5px; color: black; font-weight: bold;">Description</label>
                            <textarea
                                id="description"
                                name="description"
                                maxlength="300"
                                class="edit-textarea"
                                style="resize: none;"
                            ><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label for="picture" style="display: block; margin-bottom: 5px; color: black; font-weight: bold;">Change Picture</label>
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
                            Save
                        </button>

                    </form>

                <?php else: ?>

                    <h2><?= htmlspecialchars($project['title']) ?></h2>

                    <p class="project-description">
                        <?= nl2br(htmlspecialchars($project['description'] ?? '')) ?>
                    </p>

                    <p class="project-owner">
                        <strong>Project Owner:</strong> <?= htmlspecialchars($project['owner_name'] ?? 'Unknown') ?>
                    </p>

                <?php endif; ?>

            </div>

        </div>

    </div>

    <!-- Delete Confirmation Modal Overlay -->
    <div id="delete-modal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(4px); transition: all 0.3s ease;">
        <div class="modal-content" style="background: #DDCBB6; padding: 30px; border-radius: 8px; width: 400px; max-width: 90%; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.3); border: 2px solid #124212; transform: scale(0.9); transition: transform 0.3s ease;">
            <h3 style="color: #124212; margin-bottom: 15px; font-size: 1.5rem; font-family: 'Bitcount', sans-serif;">Delete Project</h3>
            <p style="color: #124212; margin-bottom: 25px; font-size: 1.1rem;">Are you sure you want to delete this project? This action cannot be undone.</p>
            <div style="display: flex; gap: 15px; justify-content: center;">
                <a href="index.php?page=project&id=<?= $project['id'] ?>&delete=1" class="btn" style="background: #d9534f; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px; font-family: 'Bitcount', sans-serif; display: inline-flex; align-items: center; justify-content: center;">
                    Yes, Delete
                </a>
                <button onclick="closeDeleteModal()" class="btn" style="background: #7b815a; color: white; padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; font-family: 'Bitcount', sans-serif;">
                    Cancel
                </button>
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

    function openDeleteModal(event) {
        event.preventDefault();
        const modal = document.getElementById('delete-modal');
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.querySelector('.modal-content').style.transform = 'scale(1)';
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-modal');
        modal.querySelector('.modal-content').style.transform = 'scale(0.9)';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 150);
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

<?php else: ?>

    <h2>Project not found</h2>

<?php endif; ?>
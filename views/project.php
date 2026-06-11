<?php
/** @var array|null $project */

require 'includes/header.php';
?>

<?php
$image = (!empty($project['image']) && file_exists($project['image']))
    ? $project['image']
    : 'assets/images/missing_image.png';

$editMode = isset($_GET['edit']) && $_GET['edit'] == 1;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project['title'] = $_POST['title'] ?? $project['title'];
    $project['description'] = $_POST['description'] ?? ($project['description'] ?? '');

    header("Location: index.php?page=project&id=" . $project['id']);
    exit;
}
?>

<?php if ($project): ?>

    <div class="full-container">

        <a href="index.php" class="back-button">⮜</a>

        <div class="project-container">

            <div class="project-image">
                <img
                    src="<?= $image ?>"
                    alt="<?= htmlspecialchars($project['title']) ?>"
                >
            </div>

            <div class="project-info">

                <div class="project-actions">

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
                    href="index.php?page=project&id=<?= $project['id'] ?>&delete=1">
                        Delete
                    </a>

                </div>

                <?php if ($editMode): ?>

                    <form method="POST" class="edit-form">

                        <input
                            type="text"
                            name="title"
                            value="<?= htmlspecialchars($project['title']) ?>"
                            class="edit-input"
                        >

                        <textarea
                            name="description"
                            class="edit-textarea"
                        ><?= htmlspecialchars($project['description'] ?? '') ?></textarea>

                        <button type="submit" class="action-btn save-btn">
                            Save
                        </button>

                    </form>

                <?php else: ?>

                    <h2><?= htmlspecialchars($project['title']) ?></h2>

                    <p class="project-description">
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                    </p>

                    <p class="project-owner">
                        <strong>Project Owner:</strong>
                    </p>

                <?php endif; ?>

            </div>

        </div>

    </div>

<?php else: ?>

    <h2>Project not found</h2>

<?php endif; ?>
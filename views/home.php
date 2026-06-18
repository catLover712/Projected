<?php
if (!isset($projects)) {
    header("Location: /Projected/index.php");
    exit();
}
require __DIR__ . '/../includes/header.php';
?>

<div class="project-grid">

    <?php foreach ($projects as $project): ?>

        <?php
        $image = 'assets/images/missing_image.png';
        if (!empty($project['picture'])) {
            if (is_string($project['picture']) && file_exists($project['picture'])) {
                $image = $project['picture'];
            } else {
                $image = 'data:image/jpeg;base64,' . base64_encode($project['picture']);
            }
        }
        ?>

        <a
            class="project-card"
            href="index.php?page=project&id=<?= $project['id'] ?>"
        >

            <img
                src="<?= $image ?>"
                alt="<?= htmlspecialchars($project['title']) ?>"
            >

            <div class="project-title">
                <?= htmlspecialchars($project['title']) ?>
            </div>

        </a>

    <?php endforeach; ?>

</div>
<?php
/** @var array $projects */

require 'includes/header.php';
?>



<div class="project-grid">

    <?php foreach ($projects as $project): ?>

        <?php
        $image = (!empty($project['image']) && file_exists($project['image']))
            ? $project['image']
            : 'assets/images/missing_image.png';
        ?>

        <a
            class="project-card"
            href="index.php?page=project&id=<?= $project['id'] ?>"
        >

            <img
                src="<?= $image ?>"
                alt="<?= $project['title'] ?>"
            >

            <div class="project-title">
                <?= $project['title'] ?>
            </div>

        </a>

    <?php endforeach; ?>

</div>

<?php
/** @var array|null $project */

require 'includes/header.php';
?>

<?php
$image = (!empty($project['image']) && file_exists($project['image']))
    ? $project['image']
    : 'assets/images/missing_image.png';
?>

<?php if ($project): ?>
    
    <div class="full-container">

        <a href="index.php" class="back-button">⮜</a>

        <div class="project-container">

            <div class="project-image">
                <img
                    src="<?= $image ?>"
                    alt="<?= $project['title'] ?>"
                >
            </div>

            <div class="project-info">

                <div class="project-actions">
                    <button>Bearbeiten</button>
                    <button>Löschen</button>
                </div>

                <h2><?= $project['title'] ?></h2>

                <p class="project-description">
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.  
                </p>

                <p class="project-owner">
                    <strong>Project Owner:</strong>
                </p>

            </div>

        </div>

    </div>

<?php else: ?>

    <h2>Projekt nicht gefunden</h2>

<?php endif; ?>
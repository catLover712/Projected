<?php
require __DIR__ . '/../includes/header.php';
?>

<div class="full-container">

    <div class="project-container" style="flex-direction: column; align-items: center;">

        <h2 style="margin-bottom: 20px;">Login</h2>

        <form method="post" action="/controllers/UserController.php" style="width: 100%; max-width: 400px;">

            <div id="form-group">

                <div id="email" style="margin-bottom: 15px;">
                    <label for="email">Email *</label><br />
                    <input type="email" id="email" name="email" required style="width: 100%; padding: 10px;" />
                </div>

                <div id="password" style="margin-bottom: 15px;">
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
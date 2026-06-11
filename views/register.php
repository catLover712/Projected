<?php
require __DIR__ . '/../includes/header.php';
?>

<div class="full-container">

    <div class="project-container" style="flex-direction: column; align-items: center;">

        <h2 style="margin-bottom: 20px;">Register</h2>

        <form method="post" action="/controllers/UserController.php" style="width: 100%; max-width: 400px;">

            <div id="form-group">

                <div id="username" style="margin-bottom: 15px;">
                    <label for="username">Username *</label><br />
                    <input type="text" id="username" name="username" maxlength="15" required style="width: 100%; padding: 10px;" />
                </div>

                <div id="email" style="margin-bottom: 15px;">
                    <label for="email">Email *</label><br />
                    <input type="email" id="email" name="email" maxlength="30" required style="width: 100%; padding: 10px;" />
                </div>

                <div id="password" style="margin-bottom: 15px;">
                    <label for="password">Password *</label><br />
                    <input type="password" id="password" name="password" required style="width: 100%; padding: 10px;" />
                </div>

                <div id="confirm_password" style="margin-bottom: 15px;">
                    <label for="confirm_password">Confirm Password *</label><br />
                    <input type="password" id="confirm_password" name="confirm_password" required style="width: 100%; padding: 10px;" />
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
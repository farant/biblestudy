<?php if (!isAdmin()) { header('Location: /admin'); exit; } ?>

    <div class="page-content">
        <h1 class="page-title">Change Password</h1>
        <div class="ornament">&#10022; &#9670; &#10022;</div>

        <?php if (isset($_GET['success'])): ?>
            <p class="notice" style="border-color: #8B6914;">Password changed successfully!</p>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <p class="notice">
                <?php
                switch ($_GET['error']) {
                    case 'wrong': echo 'Current password is incorrect.'; break;
                    case 'mismatch': echo 'New passwords do not match.'; break;
                    case 'short': echo 'New password must be at least 6 characters.'; break;
                    default: echo 'Something went wrong. Please try again.';
                }
                ?>
            </p>
        <?php endif; ?>

        <form action="/admin/password/save" method="POST" class="post-form" style="max-width: 400px; margin: 1.5rem auto;">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required minlength="6">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
            </div>
            <button type="submit" class="btn">Change Password</button>
        </form>

        <p class="text-center" style="margin-top: 1.5rem;">
            <a href="/admin">&larr; Back to Admin</a>
        </p>
    </div>

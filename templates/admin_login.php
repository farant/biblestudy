<?php if (isAdmin()): ?>
    <div class="page-content">
        <h1 class="page-title">Admin</h1>
        <div class="ornament">&#10022; &#9670; &#10022;</div>

        <p class="text-center">
            Logged in as <strong><?= e(adminName()) ?></strong>
        </p>

        <div class="admin-actions">
            <p><a href="/admin/password">Change Password</a></p>
            <p><a href="/admin/logout">Log Out</a></p>
        </div>

        <div class="ornament">&#10022; &#9670; &#10022;</div>

        <p class="text-center" style="color: #c4a95a;">
            While logged in, you'll see <span style="color: #c0392b;">&#10005; Delete</span>
            buttons on posts and comments across the site.
        </p>
    </div>

<?php else: ?>
    <div class="page-content">
        <h1 class="page-title">Admin Login</h1>
        <div class="ornament">&#10022; &#9670; &#10022;</div>

        <?php if (isset($_GET['error'])): ?>
            <p class="notice">Invalid username or password. Please try again.</p>
        <?php endif; ?>

        <form action="/admin/login" method="POST" class="post-form" style="max-width: 400px; margin: 1.5rem auto;">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn">Log In</button>
        </form>
    </div>
<?php endif; ?>
